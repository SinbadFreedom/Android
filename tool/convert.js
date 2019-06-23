"use strict";
const fs = require('fs');
const path = require('path');

const showdown = require('showdown');
const article_type = process.argv[2];
/** 语言版本*/
const lan_arr = [];
/** 加入支持同时转化多个语言版本*/
for (let i = 3; i < process.argv.length; i++) {
    lan_arr.push(process.argv[i]);
}

/** catalog 文件名*/
const catalogName = "catalog.txt";
/** 版本号 更新内容*/
const versionName = "version.json";

/** 全部md文件名*/
let allFileName = [];

function getAllMdFileName(folderName) {
    console.log("readFolder folderName : " + folderName);
    let files = fs.readdirSync(folderName);
    files.forEach(function (file) {
        if (file.endsWith('.md')) {
            let isNumber = isFileStartWithNumber(file);
            if (isNumber) {
                /** 只获取已经添加数字的文件,这个数字是从1开始的*/
                console.log(" sub_file: " + file);
                allFileName.push(file);
            }
        }
    });
    console.log("allFileName: " + allFileName);
}

/**
 * 输出文件名采用数字
 * 移除后缀名
 * 文件名必修以"数字"+"."开始, 否则会错
 */
function isFileStartWithNumber(fileName) {
    const fileNumber = fileName.split('.')[0];
    let intFileNumber = parseInt(fileNumber);
    if (intFileNumber) {
        /** 首字母是数字*/
        return true;
    } else {
        /** 首字母不是数字, 忽略该文件*/
        return false;
    }
}

/**
 * 读取目录中的MD文件
 * @param folderName
 */
function convertAllFile(htmlOutBaseFolder, article_folder) {
    /** 检测输出目录，不存在则创建*/
    let exist = fs.existsSync(htmlOutBaseFolder);
    if (!exist) {
        console.log('convertAllFile htmlOutBaseFolder ' + htmlOutBaseFolder);
        makeDir(htmlOutBaseFolder);
    }

    /** 转化md文件*/
    for (let index in allFileName) {
        let mdFileNameWithFolder = article_folder + "/" + allFileName[index];
        let outHtmlFileName = htmlOutBaseFolder + "/" + allFileName[index].replace('.md', '.html');
        convertSingleFile(allFileName[index], mdFileNameWithFolder, outHtmlFileName);
    }
}

/** 创建多级目录*/
function makeDir(dirpath) {
    if (!fs.existsSync(dirpath)) {
        let pathtmp;
        dirpath.split("/").forEach(function (dirname) {
            if (pathtmp) {
                pathtmp = path.join(pathtmp, dirname);
            } else {
                /** 如果在linux系统中，第一个dirname的值为空，所以赋值为"/"*/
                if (dirname) {
                    pathtmp = dirname;
                } else {
                    pathtmp = "/";
                }
            }
            if (!fs.existsSync(pathtmp)) {
                if (!fs.mkdirSync(pathtmp)) {
                    return false;
                }
            }
        });
    }
    return true;
}

/**
 * 匹配主标题 ***, 修改id，以空格为拆分，前边的作为 h1 的id
 */
showdown.extension('custom-header-id-for-title', function () {
    let setextRegexH1 = /^(.+)[ \t]*\n\*+[ \t]*\n+/gm;

    return [{
        type: 'listener',
        listeners: {
            'headers.before': function (event, text, converter, options, globals) {
                text = text.replace(setextRegexH1, function (wholeMatch, m1) {
                    let spanGamut = showdown.subParser('spanGamut')(m1, options, globals);
                    let hLevel = 1;
                    /** 通过标题名称，按空格切分，得出id*/
                    let title_id = m1.split(" ")[0];
                    /** 将id中的. 替换为_, jquery的选择器与.有冲突*/
                    title_id = title_id.replace(/\./g, '_');
                    let hashBlock = '<h' + hLevel + ' id="' + title_id + '">' + spanGamut + '</h' + hLevel + '>';
                    return showdown.subParser('hashBlock')(hashBlock, options, globals);
                });
                return text;
            }
        }
    }];
});

/**
 * 匹配一级子标题 ===, 修改id，以空格为拆分，前边的作为 h1 的id
 */
showdown.extension('custom-header-id-for-title-sub-1', function () {
    let setextRegexH1 = /^(.+)[ \t]*\n=+[ \t]*\n+/gm;

    return [{
        type: 'listener',
        listeners: {
            'headers.before': function (event, text, converter, options, globals) {
                text = text.replace(setextRegexH1, function (wholeMatch, m1) {
                    let spanGamut = showdown.subParser('spanGamut')(m1, options, globals);
                    let hLevel = 2;
                    /** 通过标题名称，按空格切分，得出id*/
                    let title_id = m1.split(" ")[0];
                    /** 将id中的. 替换为_, jquery的选择器与.有冲突*/
                    title_id = title_id.replace(/\./g, '_');
                    let hashBlock = '<h' + hLevel + ' id="' + title_id + '">' + spanGamut + '</h' + hLevel + '>';
                    return showdown.subParser('hashBlock')(hashBlock, options, globals);
                });
                return text;
            }
        }
    }];
});

/**
 * 匹配二级子标题 ---, 修改id，以空格为拆分，前边的作为 h2 的id
 */
showdown.extension('custom-header-id-for-title-sub-2', function () {
    let setextRegexH2 = /^(.+)[ \t]*\n-+[ \t]*\n+/gm;

    return [{
        type: 'listener',
        listeners: {
            'headers.before': function (event, text, converter, options, globals) {
                text = text.replace(setextRegexH2, function (wholeMatch, m1) {
                    let spanGamut = showdown.subParser('spanGamut')(m1, options, globals);
                    let hLevel = 3;
                    /** 通过标题名称，按空格切分，得出id*/
                    let title_id = m1.split(" ")[0];
                    /** 将id中的. 替换为_, jquery的选择器与.有冲突*/
                    title_id = title_id.replace(/\./g, '_');
                    let hashBlock = '<h' + hLevel + ' id="' + title_id + '">' + spanGamut + '</h' + hLevel + '>';
                    return showdown.subParser('hashBlock')(hashBlock, options, globals);
                });
                return text;
            }
        }
    }];
});


// /**
//  *  加入自定义插件改变标题id规则 只对以"#"开头的标题生效(#,##,###...######),
//  *  "==="及"---"对应的标题id 采用上边的两个方法。
//  *
//  *  插件参考：
//  *  https://jsfiddle.net/tivie/107yuueg/
//  *
//  */
// showdown.extension('custom-header-id', function () {
//     // var rgx = /^(#{1,6})[ \t]*(.+?) *\{: *#([\S]+?)\}[ \t]*#*$/gmi;
//     /** 匹配标题*/
//     let rgx = /^(#{1,6})[ \t]*(.+?) *[ \t]*#*$/gmi;
//
//     return [{
//         type: 'listener',
//         listeners: {
//             'headers.before': function (event, text, converter, options, globals) {
//                 text = text.replace(rgx, function (wm, hLevel, hText, hCustomId) {
//                     // find how many # there are at the beggining of the header
//                     // these will define the header level
//                     hLevel = hLevel.length;
//
//                     // since headers can have markdown in them (ex: # some *italic* header)
//                     // we need to pass the text to the span parser
//                     hText = showdown.subParser('spanGamut')(hText, options, globals);
//                     /**
//                      * id规则为标题字符串以空格符拆分，前边的字符串。
//                      * 比如:
//                      * 2.2.1. 源代码编码
//                      * id 为 2.2.1
//                      */
//                     let title_id = hText.split(" ")[0];
//                     /** 将id中的. 替换为_, jquery的选择器与.有冲突*/
//                     title_id = title_id.replace(/\./g, '_');
//
//                     // create the appropriate HTML
//                     let header = '<h' + hLevel + ' id="' + title_id + '">' + hText + '</h' + hLevel + '>';
//
//                     // hash block to prevent any further modification
//                     return showdown.subParser('hashBlock')(header, options, globals);
//                 });
//                 return text;
//             }
//         }
//     }];
// });

/**
 * Mardown文件转化为html文件
 * @param mdFile
 * @param outHtmlFile
 */
function convertSingleFile(fileName, mdFileNameWithFolder, outHtmlFile) {
    let mdData = fs.readFileSync(mdFileNameWithFolder, 'utf-8');
    /**
     * 加入自定义插件 改变生成标题id的规则，
     * 一级标题 ===， 二级标题 ---， 其他标题(#,##...######), 三种情况区分，用3个插件分别对应
     */
    let converter = new showdown.Converter(
        {extensions: [/*'custom-header-id',*/ 'custom-header-id-for-title', 'custom-header-id-for-title-sub-1', 'custom-header-id-for-title-sub-2']});

    let htmlData = converter.makeHtml(mdData);
    /** <code> 标签加上 google-code-pretty class, 使用正则表达式全部替换，不用正则的话，只替一个 */
    htmlData = htmlData.replace(/<pre>/g, '<pre class="prettyprint">');
    /** 全角转化为半角*/
    htmlData = fullAngleToHalfAngle(htmlData);
    /** 写入文件*/
    fs.writeFileSync(outHtmlFile, htmlData);
    console.log("convertFile OK.");
}

/** 全角转化为半角*/
function fullAngleToHalfAngle(str) {
    let s = "";
    for (let i = 0; i < str.length; i++) {
        let cCode = str.charCodeAt(i);
        /** 用chrome的google翻译, 空格会转化 charcode 160 的空格字符 */
        if (cCode === 0x3000 || cCode === 160) {
            /** 处理空格*/
            cCode = 0x0020;
        } else {
            /** 除空格外全角与半角相差 65248 (十进制)*/
            if (cCode >= 0xFF01 && cCode <= 0xFF5E) {
                cCode = cCode - 65248;
            }
        }
        s += String.fromCharCode(cCode);
    }

    /** 正则转换中文标点*/
    s = s.replace(/：/g, ':');
    s = s.replace(/。/g, '.');
    s = s.replace(/“/g, '"');
    s = s.replace(/”/g, '"');
    s = s.replace(/【/g, '[');
    s = s.replace(/】/g, ']');
    s = s.replace(/《/g, '<');
    s = s.replace(/》/g, '>');
    s = s.replace(/，/g, ',');
    s = s.replace(/？/g, '?');
    s = s.replace(/、/g, ',');
    s = s.replace(/；/g, ';');
    s = s.replace(/（/g, '(');
    s = s.replace(/）/g, ')');
    s = s.replace(/‘/g, "'");
    s = s.replace(/’/g, "'");
    s = s.replace(/『/g, "[");
    s = s.replace(/』/g, "]");
    s = s.replace(/「/g, "[");
    s = s.replace(/」/g, "]");
    s = s.replace(/﹃/g, "[");
    s = s.replace(/﹄/g, "]");
    s = s.replace(/〔/g, "{");
    s = s.replace(/〕/g, "}");
    s = s.replace(/—/g, "-");
    return s;
}

function copyFileByName(article_folder, fileName, htmlOutBaseFolder) {
    /** 直接复制txt文件,转化全/半角,android客户端 目录区 用*/
    console.log("+++++++++++++++++++++++++++++++++++++++++++++++++++++++");
    console.log("copyFileByName " + fileName);
    console.log("+++++++++++++++++++++++++++++++++++++++++++++++++++++++");
    let catalogMdName = article_folder + "/" + fileName;
    let catalogData = fs.readFileSync(catalogMdName, 'utf-8');
    /** 全角转化为半角*/
    let finalCatalogData = fullAngleToHalfAngle(catalogData);
    /** 写入文件*/
    fs.writeFileSync(htmlOutBaseFolder + "/" + fileName, finalCatalogData);
}

function convertCatalogMd(article_folder, htmlOutBaseFolder, language) {
    /** 直接复制txt文件,转化全/半角,android客户端 目录区 用*/
    console.log("+++++++++++++++++++++++++++++++++++++++++++++++++++++++");
    console.log("convertCatalog " + catalogName);
    console.log("+++++++++++++++++++++++++++++++++++++++++++++++++++++++");
    let catalogMdName = article_folder + "/" + catalogName;
    let catalogData = fs.readFileSync(catalogMdName, 'utf-8');
    /** 全角转化为半角*/
    let finalCatalogData = fullAngleToHalfAngle(catalogData);
    /** 写入文件*/
    fs.writeFileSync(catalogMdName, finalCatalogData);

    // let lines = finalCatalogData.split('\r\n');
    let lines = finalCatalogData.split('\n');

    let chapter_arr = [];

    for (let i = 0; i < lines.length; i++) {
        let line = lines[i].trim();
        console.log('line ：' + line);
        let anchor = line.split(' ')[0];
        let id = line.split('.')[0];
        /** 将id中的. 替换为_, jquery的选择器与.有冲突*/
        anchor = anchor.replace(/\./g, '_');

        let chapter_obj = {};
        chapter_obj.id = parseInt(id);
        chapter_obj.url = '/index.html?nav=doc&tag=' + article_type + '&language=' + language + '&contentid=' + id + "&anchor=" + anchor;
        chapter_obj.anchor = anchor;
        chapter_obj.title = line;

        if (i + 1 < lines.length) {
            let temp_line_1 = lines[i + 1].trim();
            let temp_anchor_sub_1 = temp_line_1.split(' ')[0];
            temp_anchor_sub_1 = temp_anchor_sub_1.replace(/\./g, '_');

            if (temp_anchor_sub_1.startsWith(anchor)) {
                /** 下一个主标题*/

                /** 标题索引自增*/
                i++;
                /** 一级子标题*/
                chapter_obj.sub_1 = [];

                for (; i < lines.length; i++) {
                    let line_sub_1 = lines[i].trim();
                    let anchor_sub_1 = line_sub_1.split(' ')[0];
                    anchor_sub_1 = anchor_sub_1.replace(/\./g, '_');

                    if (!anchor_sub_1.startsWith(anchor)) {
                        /** 下一个主标题*/
                        i--;
                        break;
                    }

                    console.log(' line_sub_1 ：' + line_sub_1);

                    let sub_1_obj = {};
                    sub_1_obj.anchor = anchor_sub_1;
                    sub_1_obj.title = line_sub_1;
                    sub_1_obj.url = '/index.html?nav=doc&tag=' + article_type + '&language=' + language + '&contentid=' + id + "&anchor=" + anchor_sub_1;

                    chapter_obj.sub_1.push(sub_1_obj);

                    if (i + 1 < lines.length) {
                        let temp_line_2 = lines[i + 1].trim();
                        let temp_anchor_2 = temp_line_2.split(' ')[0];
                        temp_anchor_2 = temp_anchor_2.replace(/\./g, '_');

                        if (temp_anchor_2.startsWith(anchor_sub_1)) {
                            /** 标题索引自增*/
                            i++;
                            /** 二级子标题*/
                            for (; i < lines.length; i++) {
                                let line_sub_2 = lines[i].trim();

                                let anchor_sub_2 = line_sub_2.split(' ')[0];
                                anchor_sub_2 = anchor_sub_2.replace(/\./g, '_');

                                if (!anchor_sub_2.startsWith(anchor_sub_1)) {
                                    /** 下一个一级标题，或者下一个主题*/
                                    i--;
                                    break;
                                }

                                console.log('   line_sub_2 ：' + line_sub_2);

                                let sub_2_obj = {};
                                sub_2_obj.anchor = anchor_sub_2;
                                sub_2_obj.title = line_sub_2;
                                sub_2_obj.url = '/index.html?nav=doc&tag=' + article_type + '&language=' + language + '&contentid=' + id + "&anchor=" + anchor_sub_2;

                                if (!sub_1_obj.sub_2) {
                                    sub_1_obj.sub_2 = [];
                                }

                                sub_1_obj.sub_2.push(sub_2_obj);
                            }
                        }
                    }
                }
            }
        }

        chapter_arr.push(chapter_obj);
    }

    /** 转化目录html，android首屏用*/
    let outHtmlFileName = htmlOutBaseFolder + "/" + catalogName.replace('.txt', '.json');
    /** 写入文件*/
    let final_data = {};
    final_data.catalog = chapter_arr;
    fs.writeFileSync(outHtmlFileName, JSON.stringify(final_data));
}

/** 组合js文件*/
function combineAllJsFile() {
    let js_folder = '../src/js/';
    let files = fs.readdirSync(js_folder);
    /** 清空all.js*/
    fs.writeFileSync('../build/js/all.js', '');
    /** 组合js*/
    files.forEach(function (file) {
        if (file.endsWith('.js')) {
            /** 只获取已经添加数字的文件,这个数字是从1开始的*/
            console.log(" sub_file: " + file);
            let jsData = fs.readFileSync(js_folder + file, 'utf-8');
            fs.writeFileSync('../build/js/all.js', jsData, {encoding: 'utf8', flag: 'a'});
        }
    });
}

/** 加入支持同时转化多个语言版本*/
for (let i = 0; i < lan_arr.length; i++) {
    const language = lan_arr[i];

    const article_folder = "../src/md/" + article_type + '/' + language;
    /** pc默认格式文件目录*/
    let htmlOutBaseFolder = "../build/doc/" + article_type + '/' + language;

    /** 获取所有目录下所有数字开头的md文件*/
    getAllMdFileName(article_folder);
    /** 转化全部文件*/
    convertAllFile(htmlOutBaseFolder, article_folder);
    /** 版本配置文件*/
    copyFileByName(article_folder, versionName, htmlOutBaseFolder);

    convertCatalogMd(article_folder, htmlOutBaseFolder, language);
}

/** 组合js文件*/
combineAllJsFile();