# 実装計画: 生産計画管理API

## フェーズ 1: データベーススキーマとモデルの実装 [checkpoint: f032f19]
- [x] タスク: `production_plans` (ヘッダー) と `production_plan_details` (明細) テーブルのマイグレーション作成 a458639
- [x] タスク: `ProductionPlan` と `ProductionPlanDetail` モデルおよびリレーションの実装 9bbe267
- [x] タスク: テスト用の Factory 作成 9bbe267
- [x] タスク: リレーションと基本モデルロジック（ステータス定数、ヘルパー等）のユニットテスト作成 9bbe267
- [x] タスク: Conductor - 手動検証 'データベーススキーマとモデルの実装' (workflow.mdのプロトコルに準拠) f032f19

## フェーズ 2: 生産計画CRUD APIの実装
- [x] タスク: バリデーション（日付妥当性、ステータス保護等）を含む `StoreProductionPlanRequest` と `UpdateProductionPlanRequest` の作成 6593597
- [~] タスク: `ProductionPlanController` の基本メソッドの実装 (index, show, store, update, destroy)
- [ ] タスク: APIレスポンス整形用の `ProductionPlanResource` の実装
- [ ] タスク: CRUD操作の機能テスト作成 (明細を含む一括作成、バリデーションエラーの確認)
- [ ] タスク: Conductor - 手動検証 '生産計画CRUD APIの実装' (workflow.mdのプロトコルに準拠)

## フェーズ 3: ステータス遷移（承認フロー）の実装
- [ ] タスク: `ProductionPlanController` にステータス遷移用エンドポイントの実装 (submit, approve, reject, cancel)
- [ ] タスク: ロールに基づく操作制限（承認・却下は Production Manager 以上等）のための Policy 実装
- [ ] タスク: ステータスワークフローの機能テスト作成 (Draft -> Pending -> Approved 等の遷移確認)
- [ ] タスク: Conductor - 手動検証 'ステータス遷移（承認フロー）の実装' (workflow.mdのプロトコルに準拠)
