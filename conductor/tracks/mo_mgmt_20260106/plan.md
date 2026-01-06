# 実装計画: 製造指示（MO）管理API

## フェーズ 1: データベーススキーマとモデルの実装 [checkpoint: 8489b1c]
- [x] タスク: `manufacturing_orders` と `mo_components` テーブルのマイグレーション作成 3832b8c
- [x] タスク: `ManufacturingOrder` と `MoComponent` モデルおよびリレーションの実装 88b90a8
- [x] タスク: テスト用の Factory 作成 975ad8d
- [x] タスク: リレーションとステータス遷移定数のユニットテスト作成 13f6423
- [x] タスク: Conductor - 手動検証 'データベーススキーマとモデルの実装' (workflow.mdのプロトコルに準拠) 8489b1c

## フェーズ 2: 製造指示 生成ロジック（Service層）の実装 [checkpoint: 578d1e1]
- [x] タスク: `MoService` クラスの作成 571d7fa
- [x] タスク: 生産計画から製造指示を生成し、BOMをスナップショット保存するロジックのユニットテスト作成 d364e99
- [x] タスク: `MoService` での生成・BOMコピーロジックの実装 42c9b9f
- [x] タスク: 自動採番（MO番号）機能の実装 42c9b9f
- [x] タスク: Conductor - 手動検証 '製造指示 生成ロジックの実装' (workflow.mdのプロトコルに準拠) 578d1e1

## フェーズ 3: API統合と管理機能の実装
- [ ] タスク: `ManufacturingOrderController` および `MoResource` の実装
- [ ] タスク: 生産計画からの発行エンドポイント (`POST /api/production-plans/{id}/release`) の実装
- [ ] タスク: 製造指示の一覧・詳細取得およびステータス更新エンドポイントの実装
- [ ] タスク: 権限（Production Manager 以上等）とステータス遷移の整合性を確認する機能テスト作成
- [ ] タスク: Conductor - 手動検証 'API統合と管理機能の実装' (workflow.mdのプロトコルに準拠)
