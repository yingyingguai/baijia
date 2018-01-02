/**
 * Created by 123 on 2018-01-02.
 */
/**分页工具条,前台界面显示效果及分页计算
 *@param {pagebar}         string                              分页控件要渲染到的html元素的ID
 *@param {pagesize}        int                                 每页大小
 *@param {onpagechangefn}  function(currentpage, pagesize){ }  当页面更改时触发的回调函数 这个函数会接收到currentpage, pagesize两个参数
 */
function PageTab(pagebar, pagesize, onpagechangefn) {

    var html = ' <span><a href="javascript:void(null)" class="fristpage">首页</a></span> ' +
        '<span><a href="javascript:void(null)" class="upPage">上一页</a></span>' +
        '<input type="text" value="1" class="currentpage"  />/ ' +
        '<span class="totalpage">1</span> ' +
        '<span><a href="javascript:void(null)" class="gopage">跳转</a></span>' +
        '<span><a href="javascript:void(null)" class="nextpage">下一页</a></span>' +
        '<span><a href="javascript:void(null)" class="lastpage">尾页</a></span>' +
        '当前显示<span class="currentrows">0-0</span>' +
        '/共<span class="totalcount">20</span>条' +
        '<input type="hidden" class="pagesize"  value="8"/>'

    var pbar = $(pagebar);
    pbar.html(html);

    //更改总记录数
    this.setTotalCount = function(totalcount) {
        pbar.find(".totalcount").text(totalcount)//更改
    }

    //更改页大小
    this.setPageSize = function(pagesize) {
        pbar.find(".pagesize").val(pagesize)
    }

    this.setPageSize(pagesize);

    var currentpage, $currentpage;
    var pagesize;
    var totalcount;

    //取值
    function getv() {
        $currentpage = pbar.find(".currentpage");
        currentpage = $currentpage.val() * 1;

        pagesize = pbar.find(".pagesize").val() * 1;
        totalcount = pbar.find(".totalcount").text() * 1;
    }


    pbar.find(".fristpage").click(function() {
        $currentpage.val(1);
        gotopage();
    })

    pbar.find(".upPage").click(function() {
        getv();
        if (currentpage > 1) {
            $currentpage.val(currentpage - 1);
            gotopage();
        }
    })

    pbar.find(".nextpage").click(function() {
        getv();
        if (currentpage <= (totalcount / pagesize)*1) {
            $currentpage.val(currentpage + 1);
            gotopage();
        }
    })

    pbar.find(".lastpage").click(function() {
        $currentpage.val(Math.ceil(totalcount / pagesize));
        gotopage();
    })

    function gotopage() {
        getv();
        if (onpagechangefn) {
            onpagechangefn(currentpage, pagesize);
        }
    }
    pbar.find(".gopage").click(gotopage)

}