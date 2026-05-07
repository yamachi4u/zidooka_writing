/**
 * Calendar Subpage GA4 Custom Events
 * 
 * Usage in Next.js (tools.zidooka.com):
 *   import { trackCalendarEvent, trackDateConfirmed, trackSituationSelected } from '@/lib/gtag-calendar';
 * 
 * Prerequisites:
 *   - gtag must be loaded globally (e.g., via @next/third-parties/google or manual script)
 *   - GA4 property must have custom event definitions (see ga4-admin-setup.mjs)
 */

/**
 * Safely call gtag if available.
 * Falls back to console.log in development if gtag is missing.
 */
function sendEvent(eventName, params = {}) {
  if (typeof window === 'undefined') return;

  const payload = {
    event_category: 'calendar',
    ...params,
  };

  if (typeof window.gtag === 'function') {
    window.gtag('event', eventName, payload);
  } else if (process.env.NODE_ENV === 'development') {
    // eslint-disable-next-line no-console
    console.log('[gtag]', eventName, payload);
  }
}

/**
 * Pain A: User opened the purpose filter dropdown and selected a value.
 * Fires when the user actively changes the filter (not on initial render).
 */
export function trackPurposeFilterUsed(purpose) {
  sendEvent('calendar_purpose_filter_used', {
    purpose: purpose || 'none',
  });
}

/**
 * Pain A/B: User clicked a lucky-day label (e.g., 大安) inside a calendar cell.
 * This may open a glossary tooltip or navigate to the explanation page.
 */
export function trackLuckydayLabelClick({ luckyday, date }) {
  sendEvent('calendar_luckyday_label_click', {
    luckyday,
    date,
  });
}

/**
 * Pain A/C: User opened the detail modal/drawer for a specific date.
 * This is the primary signal of interest in a date.
 */
export function trackDateDetailOpen({ date, luckydayCount, overlapScore, luckydayTypes = [] }) {
  sendEvent('calendar_date_detail_open', {
    date,
    luckyday_count: luckydayCount,
    overlap_score: overlapScore,
    luckyday_types: luckydayTypes.join(','),
  });
}

/**
 * Pain C: User viewed the detail of a date with overlap_score >= 2.
 * Separate event to distinguish "rare day" interest from normal date interest.
 */
export function trackOverlapDetailView({ date, overlapScore, luckydayTypes = [] }) {
  sendEvent('calendar_overlap_detail_view', {
    date,
    overlap_score: overlapScore,
    luckyday_types: luckydayTypes.join(','),
  });
}

/**
 * Pain A: User navigated to a different year.
 */
export function trackYearNav({ fromYear, toYear }) {
  sendEvent('calendar_year_nav', {
    from_year: String(fromYear),
    to_year: String(toYear),
  });
}

/**
 * Pain A: User navigated to a different month (prev/next buttons).
 */
export function trackMonthNav({ direction, fromMonth, toMonth }) {
  sendEvent('calendar_month_nav', {
    direction, // 'prev' | 'next'
    from_month: fromMonth,
    to_month: toMonth,
  });
}

/**
 * Positive signal: User shared a date via LINE, X (Twitter), or copied URL.
 */
export function trackShare({ shareType, date, source = 'calendar' }) {
  sendEvent('calendar_share', {
    share_type: shareType, // 'line' | 'twitter' | 'copy_url'
    date,
    source, // 'calendar' | 'date_detail' | 'recommendation_card'
  });
}

/**
 * UX convenience: User clicked "Jump to Today" (new feature).
 */
export function trackTodayJump() {
  sendEvent('calendar_today_jump', {});
}

/**
 * Pain B: User navigated from the calendar to an individual lucky-day explanation page.
 */
export function trackGlossaryEntry({ entryFrom, luckydayType }) {
  sendEvent('calendar_glossary_entry', {
    entry_from: entryFrom, // 'calendar_cell' | 'monthly_view' | 'external_search'
    luckyday_type: luckydayType,
  });
}

/**
 * Pain B: Scroll-depth milestones on explanation pages.
 * Use IntersectionObserver to fire these once per page load.
 */
export function trackGlossaryScroll({ luckydayType, depthPercent }) {
  const depths = [25, 50, 75, 100];
  if (!depths.includes(depthPercent)) return;

  sendEvent(`calendar_glossary_scroll${depthPercent}`, {
    luckyday_type: luckydayType,
  });
}

/**
 * Pain B: User clicked the CTA that returns from explanation page to calendar.
 */
export function trackGlossaryCtaClick({ luckydayType, targetPath }) {
  sendEvent('calendar_glossary_cta_click', {
    luckyday_type: luckydayType,
    target_path: targetPath,
  });
}

/**
 * Monetization bridge: User clicked the "next action" link inside a recommendation card.
 * This is the most critical event for monetization validation.
 */
export function trackNextActionLinkClick({ actionType, situation, targetUrl }) {
  sendEvent('calendar_next_action_link_click', {
    action_type: actionType, // e.g., 'moving_guide', 'wedding_guide', 'business_guide'
    situation,
    target_url: targetUrl,
  });
}

/**
 * Pain B: User selected a situation button on an explanation page.
 */
export function trackSituationSelected({ luckydayType, situation }) {
  sendEvent('calendar_situation_selected', {
    luckyday_type: luckydayType,
    situation, // 'marriage' | 'moving' | 'business' | 'other'
  });
}

/**
 * Pain B: User read the situation-specific guide to the end (75%+ scroll).
 */
export function trackGuideScrollCompletion({ luckydayType, situation, timeOnGuideSeconds }) {
  sendEvent('calendar_guide_scroll_completion', {
    luckyday_type: luckydayType,
    situation,
    time_on_guide: timeOnGuideSeconds,
  });
}

// ============================================================
// Higher-level helpers (use these in React components)
// ============================================================

/**
 * Tracks when a user has "confirmed" a date.
 * Heuristic: opened a date detail and did NOT open another detail within 30s.
 * Call this on component unmount or page leave if no other detail was opened.
 */
let lastOpenedDate = null;
let confirmTimer = null;

export function markDateOpened(date) {
  if (confirmTimer) clearTimeout(confirmTimer);
  lastOpenedDate = date;
}

export function markDateConfirmed({ date, luckydayTypes = [], purpose = 'none' }) {
  if (!date || date !== lastOpenedDate) return;

  sendEvent('calendar_date_confirmed', {
    date,
    luckyday_types: luckydayTypes.join(','),
    purpose,
  });

  lastOpenedDate = null;
}

/**
 * Negative signal: User opened 3+ date details in one session.
 * Call incrementDateOpenCount() on each detail open, and fire the event when threshold is reached.
 */
let dateOpenCount = 0;

export function incrementDateOpenCount() {
  dateOpenCount += 1;
  if (dateOpenCount === 3) {
    sendEvent('calendar_date_compared_3plus', {
      comparison_count: dateOpenCount,
    });
  }
}

export function resetDateOpenCount() {
  dateOpenCount = 0;
}

/**
 * Negative signal: User switched the purpose filter 3+ times in one session.
 */
let filterSwitchCount = 0;

export function incrementFilterSwitchCount() {
  filterSwitchCount += 1;
  if (filterSwitchCount === 3) {
    sendEvent('calendar_filter_switched_3plus', {
      switch_count: filterSwitchCount,
    });
  }
}

export function resetFilterSwitchCount() {
  filterSwitchCount = 0;
}

/**
 * Negative signal: Bounce under 10s on monthly view.
 * Call this from a useEffect with a 10s timer; cancel if any meaningful interaction occurs.
 */
export function scheduleBounceCheck(pageType) {
  if (typeof window === 'undefined') return () => {};

  const timer = setTimeout(() => {
    sendEvent(`calendar_${pageType}_bounce_under_${pageType === 'monthly' ? 10 : 15}s`, {
      page_type: pageType,
    });
  }, pageType === 'monthly' ? 10000 : 15000);

  return () => clearTimeout(timer);
}

/**
 * Monetization bridge: Track when a recommendation card enters the viewport.
 * Use with IntersectionObserver.
 */
export function trackRecommendationCardView({ month, purpose }) {
  sendEvent('calendar_recommendation_card_view', {
    month,
    purpose: purpose || 'none',
  });
}

/**
 * Monetization bridge: Track click on a recommendation card.
 */
export function trackRecommendationCardClick({ date, rank, purpose }) {
  sendEvent('calendar_recommendation_card_click', {
    date,
    rank, // 1 | 2 | 3
    purpose: purpose || 'none',
  });
}
