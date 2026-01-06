# 実装計画: 材料所要量計算（MRP）エンジン

## フェーズ 1: 在庫管理基盤の構築 [checkpoint: a9db1e9]
- [x] タスク: `inventories` テーブルのマイグレーション作成（id, item_id, item_type, quantity等） 9c7f4c3
- [x] タスク: `Inventory` モデルの実装と `Product`/`Material` モデルとのリレーション定義 9731a82
- [x] タスク: 在庫設定・更新用の `InventoryController` と基本APIの実装 1ced95e
- [x] タスク: 在庫管理の基本機能に関する機能テスト（Feature Test）の作成 1ced95e
- [x] タスク: Conductor - 手動検証 '在庫管理基盤の構築' (workflow.mdのプロトコルに準拠) a9db1e9

## フェーズ 2: MRP計算エンジンのコアロジック実装 [checkpoint: 3a43719]
- [x] タスク: 計算エンジンを管理する `MrpService` の作成 cb79da9
- [x] タスク: 再帰的な所要量計算（在庫考慮なし）のユニットテスト作成 f140b94
- [x] タスク: `MrpService` での基本的な再帰展開の実装 1ccb90d
- [x] タスク: 正味所要量計算（在庫引当含む）のユニットテスト作成 2b73c09
- [x] タスク: `MrpService` への在庫引当ロジックの実装（在庫がある場合は優先使用し、不足分のみ下位展開する） e034202
- [x] タスク: Conductor - 手動検証 'MRP計算エンジンのコアロジック実装' (workflow.mdのプロトコルに準拠) 3a43719

## フェーズ 3: MRP APIの統合とレスポンス形式の構築
- [x] タスク: 階層構造化された出力を提供するための `MrpCalculationResource` の実装 6718c68
- [~] タスク: `MrpController` を作成し、`POST /api/mrp/calculate` エンドポイントを実装
- [ ] タスク: MRP全体のフロー（入力: 製品/数量 -> 出力: 正味所要量）の機能テスト作成
- [ ] タスク: Conductor - 手動検証 'MRP APIの統合とレスポンス形式の構築' (workflow.mdのプロトコルに準拠)
