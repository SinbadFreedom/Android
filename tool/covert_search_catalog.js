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

// /** catalog 文件名*/
// const catalogName = "catalog.txt";
// /** 版本号 更新内容*/
// const versionName = "version.json";

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

    // let search_obj = [];
    // /** 转化md文件*/
    // for (let index in allFileName) {
    //     let mdFileNameWithFolder = article_folder + "/" + allFileName[index];
    //     convertSingleFile(mdFileNameWithFolder, search_obj);
    // }
    let mdFileNameWithFolder = article_folder + "/catalog.txt";

    let mdData = fs.readFileSync(mdFileNameWithFolder, 'utf-8');
    let lines = mdData.split('\n');
    let str = JSON.stringify(lines);
    let outHtmlFile = htmlOutBaseFolder + "/search_catalog.json";
    fs.writeFileSync(outHtmlFile, str);
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

/**
 * Mardown文件转化为html文件
 * @param mdFile
 * @param outHtmlFile
 */
function convertSingleFile(mdFileNameWithFolder, search_obj) {
    let mdData = fs.readFileSync(mdFileNameWithFolder, 'utf-8');
    let lines = mdData.split('\n');
    formatSearchText(lines, search_obj);
    // let str = JSON.stringify(search_obj);
    // console.log(str);
    // /**
    //  * 加入自定义插件 改变生成标题id的规则，
    //  * 一级标题 ===， 二级标题 ---， 其他标题(#,##...######), 三种情况区分，用3个插件分别对应
    //  */
    // let converter = new showdown.Converter(
    //     {extensions: ['custom-header-id-for-title', 'custom-header-id-for-title-sub-1', 'custom-header-id-for-title-sub-2']});
    //
    // let htmlData = converter.makeHtml(mdData);
    // /** <code> 标签加上 google-code-pretty class, 使用正则表达式全部替换，不用正则的话，只替一个 */
    // htmlData = htmlData.replace(/<pre>/g, '<pre class="prettyprint">');
    // /** 全角转化为半角*/
    // htmlData = fullAngleToHalfAngle(htmlData);
    // /** 写入文件*/
    // fs.writeFileSync(outHtmlFile, str);
    // console.log("convertFile OK.");
}

const TYPE_TITLE = 1;
const TYPE_TITLE_SUB_1 = 2;
const TYPE_TITLE_SUB_2 = 3;
const TYPE_CONTENT = 4;
const TYPE_CODE = 5;

function formatSearchText(lines, search_obj) {
    let title = '';
    for (let i = 0; i < lines.length - 1; i++) {
        let line_current = lines[i];
        let line_next = lines[i + 1];

        let line_obj = {};
        if (line_next.startsWith('***')) {
            /** 记录当前标题*/
            title = line_current;
            /** 标题*/
            line_obj.type = TYPE_TITLE;
            line_obj.line = line_current;
            // line_obj.title = title;
            i++;
        } else if (line_next.startsWith('===')) {
            /** 记录当前标题*/
            title = line_current;
            /** 一级标题*/
            line_obj.type = TYPE_TITLE_SUB_1;
            line_obj.line = line_current;
            // line_obj.title = title;
            i++;
        } else if (line_next.startsWith('---')) {
            /** 记录当前标题*/
            title = line_current;
            /** 二级标题*/
            line_obj.type = TYPE_TITLE_SUB_2;
            line_obj.line = line_current;
            // line_obj.title = title;
            i++;
        } else if (line_next.startsWith('   ')) {
            /** 代码*/
            line_obj.type = TYPE_CODE;
            line_obj.line = line_current;
            // line_obj.title = title;
        } else {
            /** 内容区*/
            line_obj.type = TYPE_CONTENT;
            line_obj.line = line_current;
            // line_obj.title = title;
        }


        if (line_current.length > 0) {
            /** 加入查找序列*/
            search_obj.push(line_obj);
        }
    }
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
}