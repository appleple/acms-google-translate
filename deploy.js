const cmd = require('node-cmd');
const fs = require('fs-extra');
const archiver = require('archiver');
const pkg = fs.readJsonSync('./package.json');

/**
 * Run system command
 *
 * @param cmdString
 * @returns {Promise}
 */
const systemCmd = cmdString => {
  return new Promise((resolve) => {
    cmd.get(
      cmdString,
      (data, err, stderr) => {
        console.log(cmdString);
        console.log(data);
        if (err) {
          console.log(err);
        }
        if (stderr) {
          console.log(stderr);
        }
        resolve(data);
      }
    );
  });
};

const zipPromise = (src, dist) => {
  return new Promise((resolve, reject) => {
    const archive = archiver.create('zip', {});
    const output = fs.createWriteStream(dist);

    // listen for all archive data to be written
    output.on('close', () => {
      console.log(archive.pointer() + ' total bytes');
      console.log('Archiver has been finalized and the output file descriptor has closed.');
      resolve();
    });

    // good practice to catch this error explicitly
    archive.on('error', (err) => {
      reject(err);
    });

    archive.pipe(output);
    archive.directory(src).finalize();
  });
};

(async () => {
  try {
    const copyFiles = fs.readdirSync('.').filter((file) => !/(\.git|\.gitignore|build|node_modules|vendor|deploy\.js)/.test(file));
    fs.mkdirsSync(`GoogleTranslate`);
    fs.mkdirsSync(`build`);
    copyFiles.forEach((file) => {
      fs.copySync(`./${file}`, `GoogleTranslate/${file}`);
    });
    await systemCmd(`cd ./GoogleTranslate; composer install`);
    await zipPromise(`GoogleTranslate`, `./build/GoogleTranslate.zip`);
    await fs.removeSync(`GoogleTranslate`);
    await systemCmd('git add -A');
    await systemCmd(`git commit -m "v${pkg.version}"`);
    await systemCmd('git push');
  } catch (err) {
    console.log(err);
  }
})();
