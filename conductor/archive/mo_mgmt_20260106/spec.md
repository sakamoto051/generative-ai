# Track Spec: Manufacturing Order (MO) Management API

## Overview
このトラックでは、承認された生産計画に基づき、現場での製造作業の最小単位となる「製造指示（Manufacturing Order）」を管理するAPIを実装します。製造指示には、その時点のBOM情報がスナップショットとして保持され、トレーサビリティの基盤となります。

## Functional Requirements

### 1. 製造指示のデータ構造
- **Manufacturing Order (`manufacturing_orders`):**
    - `id`, `mo_number` (一意の自動採番), `production_plan_detail_id` (参照元), `product_id`, `quantity`, `due_date`, `status` (Planned, Released, In Progress, Completed, Canceled), `created_by`, timestamps.
- **MO Components (`mo_components`):**
    - `id`, `manufacturing_order_id`, `item_id`, `item_type`, `required_quantity`, `unit`, timestamps.
    - ※製造指示作成時のBOM情報をここにコピーして保持する。

### 2. 製造指示 生成 API
- **生産計画からの生成 (`POST /api/production-plans/{id}/release`):**
    - 承認済み（Approved）の計画に対し、その明細ごとに製造指示を自動生成する。
    - ステータスを `Planned` または `Released` に設定。
    - 生成時に最新のBOMを展開し、`mo_components` テーブルにスナップショットを保存する。

### 3. 製造指示 管理 API
- **一覧取得 (`GET /api/manufacturing-orders`):** ステータス、納期、製品での検索・フィルタ。
- **詳細取得 (`GET /api/manufacturing-orders/{id}`):** スナップショットされた構成品リストを含む。
- **ステータス更新 (`PATCH /api/manufacturing-orders/{id}/status`):** `Planned` -> `Released` -> `In Progress` などの状態遷移。

## Non-Functional Requirements
- **採番ロジック:** ユニークなMO番号を生成する Service クラスの実装。
- **データ整合性:** 生産計画が承認されていない場合は生成を許可しない。

## Acceptance Criteria
- 承認済み生産計画からボタン一つ（API一叩き）で、全明細分の製造指示が正しい数量・構成品で生成されること。
- 生成された製造指示の構成品リストが、その後のBOMマスタ変更の影響を受けないこと。
- ステータスが正しい順序で遷移できること（例: Planned からいきなり Completed にはならない）。

## Out of Scope
- **実績入力:** 実際の製造実績（良品数・不良品数）の入力は、次以降の「製造実績管理」トラックとする。
- **ガントチャート表示:** フロントエンドでの可視化は別。
