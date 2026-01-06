# Track Spec: Manufacturing Progress Reporting API

## Overview
このトラックでは、製造現場での作業実績を報告するためのAPIを実装します。作業者は特定の製造指示（MO）に対し、完成した良品数、不良数、作業時間を記録でき、その報告に基づいて製品在庫が自動的に更新されます。

## Functional Requirements

### 1. 製造実績のデータ構造
- **Manufacturing Execution Record (`manufacturing_executions`):**
    - `id`, `manufacturing_order_id` (FK), `good_quantity` (良品数), `scrap_quantity` (不良数), `actual_duration` (分単位の作業時間), `operator_id` (User FK), `reported_at`, timestamps.

### 2. 製造実績 報告 API
- **実績登録 (`POST /api/manufacturing-orders/{id}/execute`):**
    - 特定の製造指示に対し、実績を登録。
    - **在庫連動:** 登録された `good_quantity` 分、対象製品の `inventories` レコードを増加させる。
    - **MOステータス連動:** 
        - 最初の報告時に MO のステータスを `In Progress` に変更。
        - 累積良品数が MO の計画数量に達した場合（またはユーザーが完了フラグを送った場合）、ステータスを `Completed` に変更。

### 3. 製造実績 照会 API
- **MO別実績取得 (`GET /api/manufacturing-orders/{id}/executions`):**
    - 特定のMOに紐づく過去の報告履歴を一覧取得。

## Non-Functional Requirements
- **データ整合性:** 登録はデータベーストランザクション内で行い、実績記録と在庫更新の原子性を保証する。
- **バリデーション:** 不正なMO IDや、マイナスの数量、未来の報告日時などを防止する。

## Acceptance Criteria
- `/api/manufacturing-orders/{id}/execute` を叩くと、`manufacturing_executions` にレコードが作成されること。
- 同時に、その製品の `inventories` テーブルの数量が正しく加算されていること。
- MO のステータスが `Planned` または `Released` から、報告後に `In Progress` または `Completed` に変わること。

## Out of Scope
- **原材料の自動消費 (Backflush):** 部品在庫の引き落としは今回は行わない。
- **不良理由の詳細記録:** 分類コードによる分析は別トラック。
