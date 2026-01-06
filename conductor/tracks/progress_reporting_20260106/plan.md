# 実装計画: 製造実績報告API

## フェーズ 1: データベーススキーマとモデルの実装 [checkpoint: 528219e]
- [x] タスク: `manufacturing_executions` テーブルのマイグレーション作成 0f82387
- [x] タスク: `ManufacturingExecution` モデルの実装とリレーション（MO, Operator）の定義 3e26dc0
- [x] タスク: テスト用の Factory 作成 a112471
- [x] タスク: 基本的なモデル保存とリレーションのユニットテスト作成 a0559a2
- [x] タスク: Conductor - 手動検証 'データベーススキーマとモデルの実装' (workflow.mdのプロトコルに準拠) 528219e

## フェーズ 2: 実績登録ロジック（Service層）の実装 [checkpoint: bab18ac]
- [x] タスク: `ExecutionService` クラスの作成 4a856cc
- [x] タスク: 実績登録時に在庫が増加し、MOステータスが更新されることを検証するユニットテスト作成 d9cb257
- [x] タスク: `ExecutionService` での実績保存 ＋ 在庫加算 ＋ ステータス更新ロジックの実装 9c82212
- [x] タスク: Conductor - 手動検証 '実績登録ロジックの実装' (workflow.mdのプロトコルに準拠) bab18ac

## フェーズ 3: API統合とレスポンス形式の構築
- [x] タスク: `ManufacturingOrderController` への `execute` メソッド追加および `ExecutionResource` の作成 9ad35b9
- [~] タスク: `GET /api/manufacturing-orders/{id}/executions` エンドポイントの実装
- [ ] タスク: 権限チェックとステータス連動を網羅した機能テストの作成
- [ ] タスク: Conductor - 手動検証 'API統合と管理機能の実装' (workflow.mdのプロトコルに準拠)
