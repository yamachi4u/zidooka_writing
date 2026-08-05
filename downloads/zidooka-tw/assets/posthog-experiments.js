(function () {
  'use strict';

  var initialized = false;
  var fallbackCount = 0;
  var MAX_FALLBACK = 40;
  var experimentVariants = {};

  var readyTimer = setInterval(function () {
    if (typeof posthog !== 'undefined' && posthog.__loaded) {
      clearInterval(readyTimer);
      if (typeof posthog.onFeatureFlags === 'function') {
        posthog.onFeatureFlags(init);
      }
      fallbackTimer = setInterval(pollFlags, 200);
    }
  }, 200);

  var fallbackTimer = null;

  function captureFlagError(reason, detail) {
    try {
      posthog.capture('zdk_flag_resolution_error', {
        reason: reason,
        detail: detail || '',
        posthogLoaded: typeof posthog !== 'undefined' && !!posthog.__loaded,
        hasOnFeatureFlags: typeof posthog !== 'undefined' && typeof posthog.onFeatureFlags === 'function',
        resolverUsed: fallbackTimer ? 'fallback' : (typeof posthog !== 'undefined' && typeof posthog.onFeatureFlags === 'function' ? 'onFeatureFlags' : 'none'),
      });
    } catch (e) {}
  }

  setTimeout(function () {
    clearInterval(readyTimer);
    if (fallbackTimer) {
      clearInterval(fallbackTimer);
      fallbackTimer = null;
    }
    if (!initialized) {
      captureFlagError('timeout', '20s timeout fired without initialization');
      forceInit();
    }
  }, 20000);

  function pollFlags() {
    fallbackCount++;
    if (init()) {
      clearInterval(fallbackTimer);
      fallbackTimer = null;
      return;
    }
    if (fallbackCount >= MAX_FALLBACK) {
      clearInterval(fallbackTimer);
      fallbackTimer = null;
      captureFlagError('fallback_exhausted', 'Polled ' + MAX_FALLBACK + ' times without string variant');
    }
  }

  function getFlag(key) {
    try {
      return posthog.getFeatureFlag(key);
    } catch (e) {
      return false;
    }
  }

  function getServerVariant(key) {
    var body = document.body;
    if (key === 'zdk_code_fold') {
      if (body.classList.contains('zdk-code-fold-folded')) return 'folded';
      if (body.classList.contains('zdk-code-fold-control')) return 'control';
    }
    if (key === 'zdk_header_image') {
      if (body.classList.contains('zdk-header-image-small')) return 'small';
      if (body.classList.contains('zdk-header-image-control')) return 'control';
    }
    if (key === 'zdk_author_pos') {
      if (body.classList.contains('zdk-author-pos-compact')) return 'compact';
      if (body.classList.contains('zdk-author-pos-control')) return 'control';
    }
    return false;
  }

  function capture(event, props) {
    try {
      posthog.capture(event, props);
    } catch (e) {}
  }

  function getVariants() {
    var out = {};
    for (var k in experimentVariants) {
      if (experimentVariants.hasOwnProperty(k)) {
        out[k] = experimentVariants[k];
      }
    }
    return out;
  }

  function getPath() {
    return window.location.pathname;
  }

  function getUrl() {
    return window.location.href;
  }

  function setupScrollDepth() {
    var reported = {};
    var thresholds = [25, 50, 75, 90];
    var ticking = false;
    var variants = getVariants();
    var path = getPath();
    var url = getUrl();

    function onScroll() {
      if (!ticking) {
        window.requestAnimationFrame(function () {
          var scrollTop = window.scrollY;
          var docHeight = document.documentElement.scrollHeight - window.innerHeight;
          if (docHeight <= 0) {
            ticking = false;
            return;
          }
          var percent = Math.round((scrollTop / docHeight) * 100);
          for (var i = 0; i < thresholds.length; i++) {
            var t = thresholds[i];
            if (percent >= t && !reported[t]) {
              reported[t] = true;
              capture('zdk_read_depth', {
                depth: t,
                path: path,
                url: url,
                variants: variants
              });
            }
          }
          ticking = false;
        });
        ticking = true;
      }
    }

    window.addEventListener('scroll', onScroll, { passive: true });
  }

  function setupEngagedTimer() {
    var engagedMs = 0;
    var intervalId = null;
    var variants = getVariants();
    var path = getPath();
    var url = getUrl();
    var fired = false;

    function tick() {
      engagedMs += 1000;
      if (engagedMs >= 60000 && !fired) {
        fired = true;
        capture('zdk_engaged_60s', {
          path: path,
          url: url,
          variants: variants
        });
        if (intervalId) {
          clearInterval(intervalId);
          intervalId = null;
        }
      }
    }

    function start() {
      if (intervalId) return;
      intervalId = setInterval(tick, 1000);
    }

    function pause() {
      if (intervalId) {
        clearInterval(intervalId);
        intervalId = null;
      }
    }

    document.addEventListener('visibilitychange', function () {
      if (document.visibilityState === 'visible') {
        start();
      } else {
        pause();
      }
    });

    if (document.visibilityState === 'visible') {
      start();
    }
  }

  function setupTocClicks() {
    var variants = getVariants();
    var path = getPath();
    var url = getUrl();

    document.addEventListener('click', function (e) {
      if (!e.target.closest) return;
      var link = e.target.closest('.zenn-toc-link');
      if (!link) {
        var container = e.target.closest('.zenn-toc-wrapper, .zenn-toc, [class^="toc-"], [class*=" toc-"]');
        if (container) link = container.querySelector('a');
      }
      if (link) {
        capture('zdk_toc_click', {
          text: link.textContent || '',
          href: link.href || '',
          path: path,
          url: url,
          variants: variants
        });
      }
    });
  }

  function setupRelatedClicks() {
    var variants = getVariants();
    var path = getPath();
    var url = getUrl();

    document.addEventListener('click', function (e) {
      if (!e.target.closest) return;
      var link = e.target.closest('a');
      if (link && link.closest('.zidooka-related-posts, .related-posts')) {
        capture('zdk_related_click', {
          text: link.textContent || '',
          href: link.href || '',
          path: path,
          url: url,
          variants: variants
        });
      }
    });
  }

  function setupCodeFold() {
    var variants = getVariants();
    var path = getPath();
    var url = getUrl();
    var wrapId = 0;

    var pres = document.querySelectorAll('.entry-content pre');
    for (var i = 0; i < pres.length; i++) {
      var pre = pres[i];
      if (pre.scrollHeight <= 320) continue;
      wrapId++;
      var wrap = document.createElement('div');
      wrap.className = 'zdk-code-wrap';
      wrap.id = 'zdk-code-wrap-' + wrapId;
      pre.parentNode.insertBefore(wrap, pre);
      wrap.appendChild(pre);

      var btn = document.createElement('button');
      btn.className = 'exp-code-fold-btn';
      btn.textContent = 'Show more';
      btn.setAttribute('aria-expanded', 'false');
      btn.addEventListener('click', function (w, id) {
        return function () {
          w.classList.add('exp-code-unfolded');
          btn.textContent = 'Show less';
          btn.setAttribute('aria-expanded', 'true');
          capture('zdk_code_unfold', {
            wrapId: id,
            path: path,
            url: url,
            variants: variants
          });
        };
      }(wrap, wrapId));
      wrap.appendChild(btn);
    }
  }

  function forceInit() {
    if (initialized) return true;
    window.__zdkFallbackInit = true;
    initialized = true;

    var codeFoldVal = getServerVariant('zdk_code_fold') || getFlag('zdk_code_fold');
    var headerImgVal = getServerVariant('zdk_header_image') || getFlag('zdk_header_image');
    var authorPosVal = getServerVariant('zdk_author_pos') || getFlag('zdk_author_pos');
    var headerDensityVal = getFlag('zdk_header_density');

    if (typeof codeFoldVal === 'string') {
      experimentVariants.code_fold = codeFoldVal;
      capture('zdk_experiment_impression', { experiment: 'zdk_code_fold', variant: codeFoldVal });
      if (codeFoldVal === 'folded') {
        document.body.classList.add('exp-code-fold');
        setupCodeFold();
      }
    }

    if (typeof headerImgVal === 'string') {
      experimentVariants.header_image = headerImgVal;
      capture('zdk_experiment_impression', { experiment: 'zdk_header_image', variant: headerImgVal });
    }

    if (typeof authorPosVal === 'string') {
      experimentVariants.author_pos = authorPosVal;
      capture('zdk_experiment_impression', { experiment: 'zdk_author_pos', variant: authorPosVal });
    }

    if (typeof headerDensityVal === 'string') {
      experimentVariants.header_density = headerDensityVal;
      capture('zdk_experiment_impression', { experiment: 'zdk_header_density', variant: headerDensityVal });
      if (headerDensityVal === 'compact') document.body.classList.add('zdk-header-density-compact');
    }

    if (!experimentVariants.code_fold && !experimentVariants.header_image && !experimentVariants.author_pos && !experimentVariants.header_density) {
      capture('zdk_experiment_impression', { experiment: 'fallback', variant: 'control' });
    }

    setupScrollDepth();
    setupEngagedTimer();
    setupTocClicks();
    setupRelatedClicks();
    return true;
  }

  function init() {
    if (initialized) return true;

    var codeFoldVal = getServerVariant('zdk_code_fold') || getFlag('zdk_code_fold');
    var headerImgVal = getServerVariant('zdk_header_image') || getFlag('zdk_header_image');
    var authorPosVal = getServerVariant('zdk_author_pos') || getFlag('zdk_author_pos');
    var headerDensityVal = getFlag('zdk_header_density');

    var anyResolved = typeof codeFoldVal === 'string' || typeof headerImgVal === 'string' || typeof authorPosVal === 'string' || typeof headerDensityVal === 'string';
    if (!anyResolved) {
      if (window.__zdkFallbackInit) return forceInit();
      return false;
    }

    initialized = true;

    if (typeof codeFoldVal === 'string') {
      experimentVariants.code_fold = codeFoldVal;
      capture('zdk_experiment_impression', { experiment: 'zdk_code_fold', variant: codeFoldVal });
      if (codeFoldVal === 'folded') document.body.classList.add('exp-code-fold');
    }

    if (typeof headerImgVal === 'string') {
      experimentVariants.header_image = headerImgVal;
      capture('zdk_experiment_impression', { experiment: 'zdk_header_image', variant: headerImgVal });
    }

    if (typeof authorPosVal === 'string') {
      experimentVariants.author_pos = authorPosVal;
      capture('zdk_experiment_impression', { experiment: 'zdk_author_pos', variant: authorPosVal });
    }

    if (typeof headerDensityVal === 'string') {
      experimentVariants.header_density = headerDensityVal;
      capture('zdk_experiment_impression', { experiment: 'zdk_header_density', variant: headerDensityVal });
      if (headerDensityVal === 'compact') document.body.classList.add('zdk-header-density-compact');
    }

    setupScrollDepth();
    setupEngagedTimer();
    setupTocClicks();
    setupRelatedClicks();

    return true;
  }
})();
