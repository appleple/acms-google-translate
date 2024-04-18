/**
 * 配布バージョン作成プログラム
 */

const fs = require('fs-extra');
const co = require('co');
const { zipPromise } = require('./lib/system.js');

const srcDir = 'src'
const zipDir = 'GoogleTranslate'

const ignores = [
  '.git',
  '.gitignore',
  'node_modules',
  'composer.json',
  'composer.lock',
  'package-lock.json',
  'package.json',
];

co(function* () {
  try {
    /**
     * ready plugins files
     */
    const copyFiles = fs.readdirSync(srcDir);
    fs.mkdirsSync(zipDir);
    fs.mkdirsSync('build');

    /**
     * copy plugins files
     */
    copyFiles.forEach((file) => {
      fs.copySync(`${srcDir}/${file}`, `${zipDir}/${file}`);
    });

    /**
     * Ignore files
     */
    console.log('Remove unused files.');
    console.log(ignores);
    ignores.forEach((path) => {
      fs.removeSync(`${zipDir}/${path}`);
    });

    /**
     * Copy docs
     */
    fs.copySync('docs', `${zipDir}/docs`);

    yield zipPromise(zipDir, `./build/${zipDir}.zip`);
  } catch (err) {
    console.log(err);
  } finally {
    fs.removeSync(zipDir);
  }
});
