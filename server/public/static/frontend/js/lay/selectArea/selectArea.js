layui.define(['jquery', 'form'], function (exports) {
    let $    = layui.$;
    let form = layui.form;

    let ojb = {
        init: function (
            province_filter, city_filter, district_filter,
            province_name, city_name, district_name,
            province_id, city_id, district_id
        ) {

            function areaSelect(data, element) {
                let html = '';
                for (let i in data) {
                    html += "<option value=" + data[i]['id'] + ">" + data[i]['name'] + "</option>";
                }
                $(element).html(html);
                form.render('select');
                return data[0]['id'] === undefined ? 0 : data[0]['id'];
            }

            let first_id = areaSelect(getAllProvince(), $("[name='"+province_name+"']"));
            if(province_id !== undefined){
                $("[name='"+province_name+"']").val(province_id);
                form.render('select');
                first_id= province_id;
            }

            first_id = areaSelect(getAreaChildren(first_id), $("[name='"+city_name+"']"));
            if(city_id !== undefined){
                $("[name='"+city_name+"']").val(city_id);
                form.render('select');
                first_id= city_id;
            }

            areaSelect(getAreaChildren(first_id), $("[name='"+district_name+"']"));
            if(district_id !== undefined){
                $("[name='"+district_name+"']").val(district_id);
                form.render('select');
            }

            form.on('select('+province_filter+')', function (data) {
                let first_id = areaSelect(getAreaChildren(data['value']), $("[name='"+city_name+"']"));
                areaSelect(getAreaChildren(first_id), $("[name='"+district_name+"']"));
            });

            form.on('select('+city_filter+')', function (data) {
                areaSelect(getAreaChildren(data['value']), $("[name='"+district_name+"']"));
            });
        }
    };

    exports('selectArea', ojb);
});