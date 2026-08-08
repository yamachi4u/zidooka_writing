import assert from 'node:assert/strict';
import fs from 'node:fs/promises';
import os from 'node:os';
import path from 'node:path';
import test from 'node:test';

import { PostService } from '../src/services/postService.js';

function serviceWithoutCredentials() {
  return Object.create(PostService.prototype);
}

test('normalizes an explicit WordPress-local schedule', () => {
  const service = serviceWithoutCredentials();
  assert.equal(
    service.normalizeScheduleDate('2026-08-15 09:30'),
    '2026-08-15T09:30:00'
  );
  assert.equal(
    service.normalizeScheduleDate('2026-08-15T09:30:45'),
    '2026-08-15T09:30:45'
  );
});

test('rejects malformed or impossible schedules', () => {
  const service = serviceWithoutCredentials();
  assert.throws(() => service.normalizeScheduleDate('2026/08/15 09:30'));
  assert.throws(() => service.normalizeScheduleDate('2026-02-30 09:30'));
  assert.throws(() => service.normalizeScheduleDate('2026-08-15 25:00'));
});

test('finds the next unoccupied 09:00 slot', async () => {
  const service = serviceWithoutCredentials();
  service.getDatePartsInTimezone = () => ({ year: 2026, month: 8, day: 8 });
  service.wp = {
    getFuturePosts: async () => [
      { date: '2026-08-09T09:00:00' },
      { date: '2026-08-10T09:00:00' }
    ]
  };

  assert.equal(await service.findNextScheduleSlot(), '2026-08-11T09:00:00');
});

test('publish_at forces future status without mutating the draft', async () => {
  const tempDir = await fs.mkdtemp(path.join(os.tmpdir(), 'zidooka-schedule-'));
  const draftPath = path.join(tempDir, 'article-jp.md');
  const original = `---
title: "Scheduled article"
slug: scheduled-article
publish_at: "2026-08-15 09:30"
---

Body text.
`;
  await fs.writeFile(draftPath, original, 'utf8');

  const service = serviceWithoutCredentials();
  service.imageProcessor = { processAndUpload: async () => null };
  service.loadMetadata = async () => ({ categories: [], tags: [] });

  const { data } = await service.processFile(draftPath);
  assert.equal(data.status, 'future');
  assert.equal(data.date, '2026-08-15T09:30:00');

  const { data: immediate } = await service.processFile(draftPath, {
    status: 'publish',
    ignoreFrontmatterSchedule: true
  });
  assert.equal(immediate.status, 'publish');
  assert.equal(immediate.date, undefined);
  assert.equal(await fs.readFile(draftPath, 'utf8'), original);
});

test('schedulePost passes a future override without rewriting the source file', async () => {
  const service = serviceWithoutCredentials();
  let received;
  service.post = async (filePath, overrides) => {
    received = { filePath, overrides };
    return { id: 1 };
  };

  await service.schedulePost('drafts/example-jp.md', '2026-08-15 09:30');
  assert.deepEqual(received, {
    filePath: 'drafts/example-jp.md',
    overrides: { status: 'future', date: '2026-08-15T09:30:00' }
  });
});
