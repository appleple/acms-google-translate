# acms-google-translate

a-blog cms のための「Google Translation API」連携拡張アプリになります。この拡張アプリを使用すると、
エントリー（記事）の多言語化を簡単に行うことができるようになります。

## 動作条件

* php5.5 以上
* a-blog cms for Professional or Enterprise のみ（現状スタンダードライセンスでの利用はできません）
* a-blog cms Ver. 2.10.0 以上

## ダウンロード

[Google Translate for a-blog cms](https://github.com/appleple/acms-google-translate/raw/master/build/GoogleTranslate.zip)

## 事前準備

### 「Google Translate API Key」の取得

[Google API Console](https://console.developers.google.com/) にログインし、以下の作業を行います。

* ライブラリで **Cloud Translation API** の有効化
* 認証情報で **APIキー** の作成

APIキーは必ず、**キーの制限** をかけるようにしてください。ただし、**HTTPリファラー** での制限はできませんので、IPアドレスによる制限をかけてください。

### a-blog cms の設計

サイトを多言語化するにあたり、各言語をブログを切って設計します。

例:  
日本語サイト  
　　 ┗ 日本語ブログ  
　　 ┗ 英語サイト  
　　　　 ┗英語ブログ  

## インストール

1. [Google Translate for a-blog cms](https://github.com/appleple/acms-google-translate/raw/master/build/GoogleTranslate.zip) から
zipファイルをダウンロードし、解凍したディレクトリ（GoogleTranslate）を **extension/plugins/** に設置する。

1. 管理者で a-blog cms にログインし、 拡張アプリに移動し、「Google Translate」をインストールします。

1. 多言語管理するブログ全てで、「Google Translate」拡張アプリを有効にします。

## 設定

拡張アプリをインストールすると、拡張メニューに「Google Translate」が増えます。ベースとなる言語ブログのみで設定を行なっていきます。

|設定項目|説明|
|:---|:---|
|ベース言語|翻訳元となる言語を設定します|
|Google Translate API Key|事前準備で用意した **APIキー** を設定します|
|カテゴリー作成|翻訳先の記事を作成するときに、カテゴリーも自動で複製するか設定します|
|ベース言語（このブログ）と関連づける他言語ブログを設定|ベース言語のブログと翻訳先のブログの関連を設定します|
|訳対象のフィールドを設定します|翻訳対象のカスタムフィールド名を指定します|
|eidを指定するカスタムフィールドを設定します|eidを設定するようなフィールドを指定します|
|bidを指定するカスタムフィールドを設定します|bidを設定するようなフィールドを指定します|
|cidを指定するカスタムフィールドを設定します|cidを設定するようなフィールドを指定します|

## Google帰属表示

参照: [https://cloud.google.com/translate/attribution](https://cloud.google.com/translate/attribution)

### ロゴ表示

Cloud Translation API を使用する際には、Googleへの帰属表示が必須になります。ロゴ画像をつかってリンクを表示するようにしてください。

![Google Translation Logo](https://cloud.google.com/translate/images/text-attribution.png)


詳細は [帰属表示の要件](https://cloud.google.com/translate/attribution) を確認してください。

### Translation API マークアップ

変更されていない Cloud Translation API の結果をウェブ上で公開し、検索できるようにする場合、
翻訳されるテキストを機械翻訳されたコンテンツとして指定する必要があります。

フォーマット

```html
<テキストの翻訳先言語の言語コード>-x-mtfrom-<原文の言語の言語コード>
```

#### HTML ドキュメント内の短いスニペットまたはセクションの場合

```html
<div lang="en-x-mtfrom-ja">Hello World.</div>
or
<span lang="en-x-mtfrom-ja">Hello World.</span>
```

#### ドキュメント全体またはウェブページ全体の場合

ページ全体の場合、翻訳元のドキュメントをオンラインで入手できるときは、次のように、HTML ドキュメントの ```<head>``` に ```<link>``` 要素を指定し、
```rel=""``` 属性を "alternate machine-translated-from" に、```hreflang=""``` 属性を翻訳元の言語コードに、```href=""``` を翻訳元のページに設定します。

```html
<html lang="en-x-mtfrom-ja">
<head>
    <link rel="alternate machine-translated-from" hreflang="ja" href="http://ja.example.com/hello.html">
</head>
<body>
...
```

### グローバル変数を使う

**Translation API マークアップ** を行うのに便利なグローバル変数が用意されていますので用いましょう。

|変数|説明|例|
|:---|:---|:---|
|%{TRANSLATION_LANG_BASE_CODE}|翻訳元の言語コード|ja|
|%{TRANSLATION_LANG_CODE}|現在いるページの言語コード|en, en-x-mtfrom-ja|
|%{TRANSLATED_BY_GOOGLE}|機械翻訳されたページのみ「yes」を出力|yes|
|%{TRANSLATION_ORIGIN_URL}|翻訳元記事のURL|http://ja.example.com/hello.html|

**%{TRANSLATION_LANG_CODE}** 変数は、人力翻訳、機械翻訳を判断して、**Translation API マークアップ** にあった コードを出力します。

このグローバル変数を使って、**Translation API マークアップ** は次のようにかけます。

```html
<html lang="%{TRANSLATION_LANG_CODE}">
<head>
    <!-- BEGIN_IF [%{TRANSLATED_BY_GOOGLE}/eq/yes] -->
    <link rel="alternate machine-translated-from" hreflang="%{TRANSLATION_LANG_BASE_CODE}" href="%{TRANSLATION_ORIGIN_URL}">
    <!-- END_IF -->
</head>
<body>
...
```

