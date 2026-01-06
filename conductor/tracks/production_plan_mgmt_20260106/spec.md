# Track Spec: Production Plan Management API

## Overview
このトラックでは、システムの核心となる「生産計画」を管理するためのAPIを実装します。複数の製品を含む計画を「ヘッダー・明細形式」で保持し、下書きから承認、確定までのフルステータス管理を可能にします。

## Functional Requirements

### 1. 生産計画のデータ構造
- **Production Plan Header (`production_plans`):**
    - `id`, `plan_code` (一意のコード), `name` (計画名), `start_date`, `end_date`, `status` (Draft, Pending, Approved, Rejected, Canceled), `created_by`, timestamps.
- **Production Plan Detail (`production_plan_details`):**
    - `id`, `production_plan_id` (FK), `product_id` (FK), `quantity`, `due_date` (納期), `priority` (優先度), `remarks` (備考), timestamps.

### 2. 生産計画 CRUD API
- **一覧取得 (`GET /api/production-plans`):** 期間やステータスでのフィルタリング、ページネーション。
- **詳細取得 (`GET /api/production-plans/{id}`):** ヘッダーと全明細を一括取得。
- **作成 (`POST /api/production-plans`):** ヘッダーと明細をトランザクション内で作成。
- **更新 (`PUT /api/production-plans/{id}`):** `Draft` ステータス時のみ変更可能。
- **削除 (`DELETE /api/production-plans/{id}`):** `Draft` ステータス時のみ論理削除。

### 3. ステータス遷移（承認） API
- **承認申請 (`POST /api/production-plans/{id}/submit`):** ステータスを `Draft` -> `Pending` に変更。
- **承認/却下 (`POST /api/production-plans/{id}/approve`, `POST /api/production-plans/{id}/reject`):** `Pending` -> `Approved` または `Rejected`。
- **取消 (`POST /api/production-plans/{id}/cancel`):** 任意の状態から `Canceled` へ。

## Non-Functional Requirements
- **データ整合性:** ヘッダー期間外の納期を持つ明細の登録をバリデーションで防ぐ。
- **認可:** 作成・更新・削除は `Production Manager` 以上のロールに制限。閲覧は全認証ユーザー。

## Acceptance Criteria
- ヘッダー1件に対し、複数の製品明細（それぞれ異なる納期）を一括で登録できること。
- `Pending` 状態の計画は、通常の更新APIでは編集できないこと（ステータス保護）。
- 登録された製品明細に対し、将来的にMRPエンジンを呼び出すための準備（リレーション）が整っていること。

## Out of Scope
- **自動スケール調整:** 設備能力を考慮した自動的な日程調整（スケジューリングエンジン）は別トラック。
- **製造指示自動生成:** `Approved` から製造指示への変換は別トラック。
- **Excelインポート:** 今回はAPI経由の登録に絞り、一括インポートは別トラック。
