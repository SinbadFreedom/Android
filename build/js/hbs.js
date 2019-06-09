/** 笔记模板数据，对应/hbs/ask_list.hbs*/
const hbs_ask_list = '<table class="table">\n' +
    '    <tbody>\n' +
    '    {{#ask}}\n' +
    '        <tr>\n' +
    '            <td class="text-center" width="30px" style="vertical-align: middle; font-weight: initial; font-size: 14px">\n' +
    '                <b>{{comment_count}}</b>\n' +
    '            </td>\n' +
    '            <td style="font-size: 18px">\n' +
    '                <a ask_tag="{{tag}}" ask_content_id="{{contentid}}" href="{{url_content}}">{{title}}</a>\n' +
    '                <div class="d-lg-none" style="font-size: 14px">\n' +
    '                    <div>\n' +
    '                        <span>{{authorname}}</span><span>{{createtime}}</span><span>{{editorname}}</span>{{edittime}}\n' +
    '                    </div>\n' +
    '                </div>\n' +
    '            </td>\n' +
    '            <td class="d-none d-lg-block text-right">\n' +
    '                <span>{{authorname}}</span><span>{{createtime}}</span><span>{{editorname}}</span>{{edittime}}\n' +
    '            </td>\n' +
    '        </tr>\n' +
    '    {{/ask}}\n' +
    '    </tbody>\n' +
    '</table>';

/** 笔记模板数据，对应/hbs/ask_info.hbs*/
const hbs_ask_info = '<div class="row">\n' +
    '    <button type="button" id="ask_info_tag" class="btn btn-light active">{{tag}}</button>\n' +
    '</div>\n' +
    '\n' +
    '<div style="display: none">\n' +
    '    <span id="ask_info_content_id">{{contentid}}</span>\n' +
    '</div>\n' +
    '\n' +
    '<table class="table">\n' +
    '    <tbody>\n' +
    '    <tr>\n' +
    '        <td width="96px">\n' +
    '            <img src="{{author_figure}}" width="48px" height="48px">\n' +
    '            <div class="text-center">\n' +
    '                <span>{{authorname}}</span>\n' +
    '            </div>\n' +
    '        </td>\n' +
    '        <td width="100%" valign="top">\n' +
    '            <div class="row">\n' +
    '                <span class="ml-auto"><small>{{createtime}}</small></span>\n' +
    '            </div>\n' +
    '            <div class="row">\n' +
    '                <span>{{title}}</span>\n' +
    '            </div>\n' +
    '            <div>\n' +
    '                <span>{{content}}</span>\n' +
    '            </div>\n' +
    '        </td>\n' +
    '    </tr>\n' +
    '    </tbody>\n' +
    '</table>\n' +
    '\n' +
    '<div id="ask_info_reply_area">\n' +
    '问答回复区\n' +
    '</div>\n' +
    '\n' +
    '<textarea class=form-control id="ask_info_txt_reply" rows="5" placeholder="输入内容"></textarea>\n' +
    '\n' +
    '<div id="ask_info_login_state">\n' +
    '    <button class="btn btn-warning" id="ask_info_btn_login">登陆后可提交</button>\n' +
    '<!--    <button id="ask_info_btn_reply" class="btn btn-primary">回复</button>-->\n' +
    '</div>';

/** 笔记模板数据，对应/hbs/note.hbs*/
const hbs_note = '<table>\n' +
    '    <tbody>\n' +
    '    {{#notes}}\n' +
    '        <tr>\n' +
    '            <td width="96px">\n' +
    '                <img src="{{editor_figure}}" width="48px" height="48px">\n' +
    '                <div class="text-center">\n' +
    '                    <span>{{editor_name}}</span>\n' +
    '                </div>\n' +
    '            </td>\n' +
    '            <td width="100%" valign="top">\n' +
    '                <div class="row">\n' +
    '                    <span class="ml-auto"><small>{{edit_time}}</small></span>\n' +
    '                </div>\n' +
    '                <div>\n' +
    '                    <span>{{reply}}</span>\n' +
    '                </div>\n' +
    '            </td>\n' +
    '        </tr>\n' +
    '    {{/notes}}\n' +
    '    </tbody>\n' +
    '</table>\n' +
    '\n' +
    '<div class="btn-group">\n' +
    '    {{#page_next}}\n' +
    '        <button id="note_page_next" class="btn btn-light">&or;</button>\n' +
    '    {{/page_next}}\n' +
    '</div>\n';