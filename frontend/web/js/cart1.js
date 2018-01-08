$(function(){


    //减少
    $(".reduce_num").click(function(){
        var amount = $(this).parent().find(".amount");
        if (parseInt($(amount).val()) <= 1){
            alert("商品数量最少为1");
        } else{
            $(amount).val(parseInt($(amount).val()) - 1);
        }
        //小计
        var subtotal = parseFloat($(this).parent().parent().find(".col3 span").text()) * parseInt($(amount).val());
        $(this).parent().parent().find(".col5 span").text(subtotal.toFixed(2));
        //总计金额
        var total = 0;
        $(".col5 span").each(function(){
            total += parseFloat($(this).text());
        });

        $("#total").text(total.toFixed(2));

        //>>减少
        var g_id=$(this).closest('tr').attr('id')
        changeNum(g_id,$(amount).val());
    });

    //增加
    $(".add_num").click(function(){
        var amount = $(this).parent().find(".amount");
        $(amount).val(parseInt($(amount).val()) + 1);
        //小计
        var subtotal = parseFloat($(this).parent().parent().find(".col3 span").text()) * parseInt($(amount).val());
        $(this).parent().parent().find(".col5 span").text(subtotal.toFixed(2));
        //总计金额
        var total = 0;
        $(".col5 span").each(function(){
            total += parseFloat($(this).text());
        });

        $("#total").text(total.toFixed(2));

        //>>增加
        var g_id=$(this).closest('tr').attr('id')
        changeNum(g_id,$(amount).val());
    });

    //直接输入
    $(".amount").blur(function(){
        if (parseInt($(this).val()) < 1){
            alert("商品数量最少为1");
            $(this).val(1);
        }
        //小计
        var subtotal = parseFloat($(this).parent().parent().find(".col3 span").text()) * parseInt($(this).val());
        $(this).parent().parent().find(".col5 span").text(subtotal.toFixed(2));
        //总计金额
        var total = 0;
        $(".col5 span").each(function(){
            total += parseFloat($(this).text());
        });

        $("#total").text(total.toFixed(2));
        //>>直接输入
        var g_id=$(this).closest('tr').attr('id')
        changeNum(g_id,$(this).val());
    });
    var changeNum = function (g_id,count) {
        $.post('cart-edit',{g_id:g_id,count:count})
    }
});