---
title: "Codexのレートリミット、ほぼ使っていないのに4%になっていた話"
slug: codex-rate-limit-4percent-jp
date: 2026-03-12T13:40:00
categories: 
  - ai-error
tags: 
  - codex
  - OpenAI
  - レートリミット
  - 使用量
status: publish
id: 3997
---


<p>2026年3月12日時点の短いメモです。</p>



<p>Codexをほぼ使っていない感覚だったのに、残量表示がすでに4%まで減っていて、レートリミットの減り方にかなり違和感がありました。</p>



<div class="wp-block-group zdk_b_conclusion is-layout-flow wp-block-group-is-layout-flow">

<p>「ほとんど使っていないのに残量だけ急に減る」なら、使い方だけでなく、サービス側の集計や障害も疑ったほうがよさそうです。</p>


</div>



<p>短時間に大量送信した覚えがない場合は、表示の遅延や一時的な不具合の可能性もあります。</p>



<p>実際、OpenAIの公式ステータスでは、2026年3月6日から3月7日にかけて <a href="https://status.openai.com/incidents/01KK26XE1W536H7DQV2EXM3GHE">Issues with Increased Codex Usage Rate</a> が案内されていました。</p>



<div class="wp-block-group zdk_b_note is-layout-flow wp-block-group-is-layout-flow">

<p>同じ症状が出たら、まず <span class="zdk_i_code">/status</span> で表示を確認しつつ、<a href="https://status.openai.com/">OpenAI Status</a> も見ておくと切り分けしやすいです。</p>


</div>



