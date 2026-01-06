# 実装計画: 製造実績報告API

## フェーズ 1: データベーススキーマとモデルの実装
- [x] タスク: `manufacturing_executions` テーブルのマイグレーション作成 0f82387
- [x] タスク: `ManufacturingExecution` モデルの実装とリレーション（MO, Operator）の定義 3e26dc0
- [x] タスク: テスト用の Factory 作成 a112471
- [~] タスク: 基本的なモデル保存とリレーションのユニットテスト作成
- [ ] タスク: Conductor - 手動検証 'データベーススキーマとモデルの実装' (workflow.mdのプロトコルに準拠)

## フェーズ 2: 実績登録ロジック（Service層）の実装
- [ ] タスク: `ExecutionService` クラスの作成
- [ ] タスク: 実績登録時に在庫が増加し、MOステータスが更新されることを検証するユニットテスト作成
- [ ] タスク: `ExecutionService` での実績保存 ＋ 在庫加算 ＋ ステータス更新ロジックの実装
- [ ] タスク: Conductor - 手動検証 '実績登録ロジックの実装' (workflow.mdのプロトコルに準拠)

## フェーズ 3: API統合とレスポンス形式の構築
- [ ] タスク: `ManufacturingOrderController` への `execute` メソッド追加および `ExecutionResource` の作成
- [ ] タスク: `GET /api/manufacturing-orders/{id}/executions` エンドポイントの実装
- [ ] タスク: 権限チェックとステータス連動を網羅した機能テストの作成
- [ ] タスク: Conductor - 手動検証 'API統合と管理機能の実装' (workflow.mdのプロトコルに準拠)
