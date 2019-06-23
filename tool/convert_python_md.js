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
function convertAllFile(htmlOutBaseFolder, article_folder, handlebars_template_file_name, language) {
    /** 检测输出目录，不存在则创建*/
    let exist = fs.existsSync(htmlOutBaseFolder);
    if (!exist) {
        console.log('convertAllFile htmlOutBaseFolder ' + htmlOutBaseFolder);
        makeDir(htmlOutBaseFolder);
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
 * 匹配一级标题 ===, 修改id，以空格为拆分，前边的作为 h1 的id
 */
showdown.extension('custom-header-id-for-title-1', function () {
    let setextRegexH1 = /^(.+)[ \t]*\n=+[ \t]*\n+/gm;

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
 * 匹配二级标题 ---, 修改id，以空格为拆分，前边的作为 h2 的id
 */
showdown.extension('custom-header-id-for-title-2', function () {
    let setextRegexH2 = /^(.+)[ \t]*\n-+[ \t]*\n+/gm;

    return [{
        type: 'listener',
        listeners: {
            'headers.before': function (event, text, converter, options, globals) {
                text = text.replace(setextRegexH2, function (wholeMatch, m1) {
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
 *  加入自定义插件改变标题id规则 只对以"#"开头的标题生效(#,##,###...######),
 *  "==="及"---"对应的标题id 采用上边的两个方法。
 *
 *  插件参考：
 *  https://jsfiddle.net/tivie/107yuueg/
 *
 */
showdown.extension('custom-header-id', function () {
    // var rgx = /^(#{1,6})[ \t]*(.+?) *\{: *#([\S]+?)\}[ \t]*#*$/gmi;
    /** 匹配标题*/
    var rgx = /^(#{1,6})[ \t]*(.+?) *[ \t]*#*$/gmi;

    return [{
        type: 'listener',
        listeners: {
            'headers.before': function (event, text, converter, options, globals) {
                text = text.replace(rgx, function (wm, hLevel, hText, hCustomId) {
                    // find how many # there are at the beggining of the header
                    // these will define the header level
                    hLevel = hLevel.length;

                    // since headers can have markdown in them (ex: # some *italic* header)
                    // we need to pass the text to the span parser
                    hText = showdown.subParser('spanGamut')(hText, options, globals);
                    /**
                     * id规则为标题字符串以空格符拆分，前边的字符串。
                     * 比如:
                     * 2.2.1. 源代码编码
                     * id 为 2.2.1
                     */
                    let title_id = hText.split(" ")[0];
                    /** 将id中的. 替换为_, jquery的选择器与.有冲突*/
                    title_id = title_id.replace(/\./g, '_');

                    // create the appropriate HTML
                    let header = '<h' + hLevel + ' id="' + title_id + '">' + hText + '</h' + hLevel + '>';

                    // hash block to prevent any further modification
                    return showdown.subParser('hashBlock')(header, options, globals);
                });
                // return the changed text
                return text;
            }
        }
    }];
});


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

/** 加入支持同时转化多个语言版本*/
for (let i = 0; i < lan_arr.length; i++) {
    const language = lan_arr[i];

    const article_folder = "../src/md/" + article_type + '/' + language;
    /** handlebars 模板文件*/
    let template_file_name_doc = "../src/md/" + article_type + "/template_doc.handlebars";
    /** pc默认格式文件目录*/
    let htmlOutBaseFolder = "../build/doc/" + article_type + '/' + language;

    /** 获取所有目录下所有数字开头的md文件*/
    getAllMdFileName(article_folder);
    /** 转化全部文件*/
    convertAllFile(htmlOutBaseFolder, article_folder, template_file_name_doc, language);
}