# acms-google_translate

## 動作条件

* php5.5 以上
* a-blog cms Ver. 2.10.0 以上

## ダウンロード

[Google Translate for a-blog cms](https://github.com/appleple/acms-google-translate/raw/master/build/GoogleTranslate.zip)

## 帰属表示

参照: [https://cloud.google.com/translate/attribution](https://cloud.google.com/translate/attribution)

### ロゴ表示

ToDo...

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

