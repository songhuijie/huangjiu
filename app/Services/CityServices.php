<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2020/1/3
 * Time: 15:09
 */
namespace App\Services;
class CityServices{

    const CITY = [
        0=>"北京市",
        1=>"天津市",
        2=>"河北省",
        3=>"山西省",
        4=>"内蒙古自治区",
        5=>"辽宁省",
        6=>"吉林省",
        7=>"黑龙江省",
        8=>"上海市",
        9=>"江苏省",
        10=>"浙江省",
        11=>"安徽省",
        12=>"福建省",
        13=>"江西省",
        14=>"山东省",
        15=>"河南省",
        16=>"湖北省",
        17=>"湖南省",
        18=>"广东省",
        19=>"广西壮族自治区",
        20=>"海南省",
        21=>"重庆市",
        22=>"四川省",
        23=>"贵州省",
        24=>"云南省",
        25=>"西藏区",
        26=>"陕西省",
        27=>"甘肃省",
        28=>"青海省",
        29=>"宁夏区",
        30=>"新疆区",
        31=>"台湾省",
        32=>"香港特区",
        33=>"澳门特区",
        34=>"境外",
        35=>"北京辖区",
        36=>"北京辖县",
        37=>"天津辖区",
        38=>"天津辖县",
        39=>"石家庄市",
        40=>"唐山市",
        41=>"秦皇岛市",
        42=>"邯郸市",
        43=>"邢台市",
        44=>"保定市",
        45=>"张家口市",
        46=>"承德市",
        47=>"沧州市",
        48=>"廊坊市",
        49=>"衡水市",
        50=>"太原市",
        51=>"大同市",
        52=>"阳泉市",
        53=>"长治市",
        54=>"晋城市",
        55=>"朔州市",
        56=>"晋中市",
        57=>"运城市",
        58=>"忻州市",
        59=>"临汾市",
        60=>"吕梁市",
        61=>"呼和浩特市",
        62=>"包头市",
        63=>"乌海市",
        64=>"赤峰市",
        65=>"通辽市",
        66=>"鄂尔多斯市",
        67=>"呼伦贝尔市",
        68=>"巴彦淖尔市",
        69=>"乌兰察布市",
        70=>"兴安盟锡林郭勒盟阿拉善盟",
        71=>"沈阳市",
        72=>"大连市",
        73=>"鞍山市",
        74=>"抚顺市",
        75=>"本溪市",
        76=>"丹东市",
        77=>"锦州市",
        78=>"营口市",
        79=>"阜新市",
        80=>"辽阳市",
        81=>"盘锦市",
        82=>"铁岭市",
        83=>"朝阳市",
        84=>"葫芦岛市",
        85=>"长春市",
        86=>"吉林市",
        87=>"四平市",
        88=>"辽源市",
        89=>"通化市",
        90=>"白山市",
        91=>"松原市",
        92=>"白城市",
        93=>"延边自治州",
        94=>"哈尔滨市",
        95=>"齐齐哈尔市",
        96=>"鸡西市",
        97=>"鹤岗市",
        98=>"双鸭山市",
        99=>"大庆市",
        100=>"伊春市",
        101=>"佳木斯市",
        102=>"七台河市",
        103=>"牡丹江市",
        104=>"黑河市",
        105=>"绥化市",
        106=>"大兴安岭地区",
        107=>"上海辖区",
        108=>"上海辖县",
        109=>"南京市",
        110=>"无锡市",
        111=>"徐州市",
        112=>"常州市",
        113=>"苏州市",
        114=>"南通市",
        115=>"连云港市",
        116=>"淮安市",
        117=>"盐城市",
        118=>"扬州市",
        119=>"镇江市",
        120=>"泰州市",
        121=>"宿迁市",
        122=>"杭州市",
        123=>"宁波市",
        124=>"温州市",
        125=>"嘉兴市",
        126=>"湖州市",
        127=>"绍兴市",
        128=>"金华市",
        129=>"衢州市",
        130=>"舟山市",
        131=>"台州市",
        132=>"丽水市",
        133=>"合肥市",
        134=>"芜湖市",
        135=>"蚌埠市",
        136=>"淮南市",
        137=>"马鞍山市",
        138=>"淮北市",
        139=>"铜陵市",
        140=>"安庆市",
        141=>"黄山市",
        142=>"滁州市",
        143=>"阜阳市",
        144=>"宿州市",
        145=>"巢湖市",
        146=>"六安市",
        147=>"亳州市",
        148=>"池州市",
        149=>"宣城市",
        150=>"福州市",
        151=>"厦门市",
        152=>"莆田市",
        153=>"三明市",
        154=>"泉州市",
        155=>"漳州市",
        156=>"南平市",
        157=>"龙岩市",
        158=>"宁德市",
        159=>"南昌市",
        160=>"景德镇市",
        161=>"萍乡市",
        162=>"九江市",
        163=>"新余市",
        164=>"鹰潭市",
        165=>"赣州市",
        166=>"吉安市",
        167=>"宜春市",
        168=>"抚州市",
        169=>"上饶市",
        170=>"济南市",
        171=>"青岛市",
        172=>"淄博市",
        173=>"枣庄市",
        174=>"东营市",
        175=>"烟台市",
        176=>"潍坊市",
        177=>"济宁市",
        178=>"泰安市",
        179=>"威海市",
        180=>"日照市",
        181=>"莱芜市",
        182=>"临沂市",
        183=>"德州市",
        184=>"聊城市",
        185=>"滨州市",
        186=>"菏泽市",
        187=>"郑州市",
        188=>"开封市",
        189=>"洛阳市",
        190=>"平顶山市",
        191=>"安阳市",
        192=>"鹤壁市",
        193=>"新乡市",
        194=>"焦作市",
        195=>"济源市",
        196=>"沁阳市",
        197=>"孟州市",
        198=>"濮阳市",
        199=>"许昌市",
        200=>"漯河市",
        201=>"三门峡市",
        202=>"南阳市",
        203=>"商丘市",
        204=>"信阳市",
        205=>"周口市",
        206=>"驻马店市",
        207=>"",
        208=>"武汉市",
        209=>"黄石市",
        210=>"十堰市",
        211=>"宜昌市",
        212=>"襄阳市",
        213=>"鄂州市",
        214=>"荆门市",
        215=>"孝感市",
        216=>"荆州市",
        217=>"黄冈市",
        218=>"咸宁市",
        219=>"随州市",
        220=>"恩施自治州",
        221=>"湖北省辖单位",
        222=>"长沙市",
        223=>"株洲市",
        224=>"湘潭市",
        225=>"衡阳市",
        226=>"邵阳市",
        227=>"岳阳市",
        228=>"常德市",
        229=>"张家界市",
        230=>"益阳市",
        231=>"郴州市",
        232=>"永州市",
        233=>"怀化市",
        234=>"娄底市",
        235=>"湘西自治州",
        236=>"广州市",
        237=>"韶关市",
        238=>"深圳市",
        239=>"珠海市",
        240=>"汕头市",
        241=>"佛山市",
        242=>"江门市",
        243=>"湛江市",
        244=>"茂名市",
        245=>"肇庆市",
        246=>"惠州市",
        247=>"梅州市",
        248=>"汕尾市",
        249=>"河源市",
        250=>"阳江市",
        251=>"清远市",
        252=>"东莞市",
        253=>"中山市",
        254=>"潮州市",
        255=>"揭阳市",
        256=>"云浮市",
        257=>"南宁市",
        258=>"柳州市",
        259=>"桂林市",
        260=>"梧州市",
        261=>"北海市",
        262=>"防城港市",
        263=>"钦州市",
        264=>"贵港市",
        265=>"玉林市",
        266=>"百色市",
        267=>"贺州市",
        268=>"河池市",
        269=>"来宾市",
        270=>"崇左市",
        271=>"海口市",
        272=>"三亚市",
        273=>"海南直辖县",
        274=>"重庆辖区",
        275=>"重庆辖县",
        276=>"成都市",
        277=>"自贡市",
        278=>"攀枝花市",
        279=>"泸州市",
        280=>"德阳市",
        281=>"绵阳市",
        282=>"广元市",
        283=>"遂宁市",
        284=>"内江市",
        285=>"乐山市",
        286=>"南充市",
        287=>"眉山市",
        288=>"宜宾市",
        289=>"广安市",
        290=>"达州市",
        291=>"雅安市",
        292=>"巴中市",
        293=>"资阳市",
        294=>"阿坝自治州",
        295=>"甘孜自治州",
        296=>"凉山自治州",
        297=>"贵阳市",
        298=>"六盘水市",
        299=>"遵义市",
        300=>"安顺市",
        301=>"铜仁地区",
        302=>"黔西南自治州",
        303=>"毕节地区",
        304=>"黔东南自治州",
        305=>"黔南自治州",
        306=>"昆明市",
        307=>"曲靖市",
        308=>"玉溪市",
        309=>"保山市",
        310=>"昭通市",
        311=>"丽江市",
        312=>"思茅市",
        313=>"临沧市",
        314=>"楚雄自治州",
        315=>"红河自治州",
        316=>"文山自治州",
        317=>"西双版纳州",
        318=>"大理自治州",
        319=>"德宏自治州",
        320=>"怒江傈自治州",
        321=>"迪庆自治州",
        322=>"拉萨市",
        323=>"昌都地区",
        324=>"山南地区",
        325=>"日喀则地区",
        326=>"那曲地区",
        327=>"阿里地区",
        328=>"林芝地区",
        329=>"西安市",
        330=>"铜川市",
        331=>"宝鸡市",
        332=>"咸阳市",
        333=>"渭南市",
        334=>"延安市",
        335=>"汉中市",
        336=>"榆林市",
        337=>"安康市",
        338=>"商洛市",
        339=>"兰州市",
        340=>"嘉峪关市",
        341=>"金昌市",
        342=>"白银市",
        343=>"天水市",
        344=>"武威市",
        345=>"张掖市",
        346=>"平凉市",
        347=>"酒泉市",
        348=>"庆阳市",
        349=>"定西市",
        350=>"陇南市",
        351=>"临夏自治州",
        352=>"甘南自治州",
        353=>"西宁市",
        354=>"海东地区",
        355=>"海北自治州",
        356=>"黄南自治州",
        357=>"海南自治州",
        358=>"果洛自治州",
        359=>"玉树自治州",
        360=>"海西自治州",
        361=>"银川市",
        362=>"石嘴山市",
        363=>"吴忠市",
        364=>"固原市",
        365=>"中卫市",
        366=>"乌鲁木齐市",
        367=>"克拉玛依市",
        368=>"吐鲁番地区",
        369=>"哈密地区",
        370=>"昌吉自治州",
        371=>"博尔塔拉州",
        372=>"巴音郭楞州",
        373=>"阿克苏地区",
        374=>"克孜勒苏州喀什地区",
        375=>"和田地区",
        376=>"伊犁自治州塔城地区",
        377=>"阿勒泰地区",
        378=>"新疆省辖单位",
        379=>"台北市",
        380=>"高雄市",
        381=>"基隆市",
        382=>"台中市",
        383=>"台南市",
        384=>"新竹市",
        385=>"嘉义市",
        386=>"香港岛九龙",
        387=>"新界东",
        388=>"新界西",
        389=>"花地玛堂区",
        390=>"圣安多尼堂区",
        391=>"花王堂区",
        392=>"大堂区",
        393=>"望德堂区",
        394=>"风顺堂区",
        395=>"境外地区",
        396=>"其他地区",
    ];

    const HIERARCHY = [

    ];
}