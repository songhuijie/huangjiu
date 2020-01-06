<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2020/1/3
 * Time: 15:09
 */
namespace App\Services;
use App\Model\Freight;

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
        70=>"兴安盟",
        71=>"锡林郭勒盟",
        72=>"阿拉善盟",
        73=>"沈阳市",
        74=>"大连市",
        75=>"鞍山市",
        76=>"抚顺市",
        77=>"本溪市",
        78=>"丹东市",
        79=>"锦州市",
        80=>"营口市",
        81=>"阜新市",
        82=>"辽阳市",
        83=>"盘锦市",
        84=>"铁岭市",
        85=>"朝阳市",
        86=>"葫芦岛市",
        87=>"长春市",
        88=>"吉林市",
        89=>"四平市",
        90=>"辽源市",
        91=>"通化市",
        92=>"白山市",
        93=>"松原市",
        94=>"白城市",
        95=>"延边自治州",
        96=>"哈尔滨市",
        97=>"齐齐哈尔市",
        98=>"鸡西市",
        99=>"鹤岗市",
        100=>"双鸭山市",
        101=>"大庆市",
        102=>"伊春市",
        103=>"佳木斯市",
        104=>"七台河市",
        105=>"牡丹江市",
        106=>"黑河市",
        107=>"绥化市",
        108=>"大兴安岭地区",
        109=>"上海辖区",
        110=>"上海辖县",
        111=>"南京市",
        112=>"无锡市",
        113=>"徐州市",
        114=>"常州市",
        115=>"苏州市",
        116=>"南通市",
        117=>"连云港市",
        118=>"淮安市",
        119=>"盐城市",
        120=>"扬州市",
        121=>"镇江市",
        122=>"泰州市",
        123=>"宿迁市",
        124=>"杭州市",
        125=>"宁波市",
        126=>"温州市",
        127=>"嘉兴市",
        128=>"湖州市",
        129=>"绍兴市",
        130=>"金华市",
        131=>"衢州市",
        132=>"舟山市",
        133=>"台州市",
        134=>"丽水市",
        135=>"合肥市",
        136=>"芜湖市",
        137=>"蚌埠市",
        138=>"淮南市",
        139=>"马鞍山市",
        140=>"淮北市",
        141=>"铜陵市",
        142=>"安庆市",
        143=>"黄山市",
        144=>"滁州市",
        145=>"阜阳市",
        146=>"宿州市",
        147=>"巢湖市",
        148=>"六安市",
        149=>"亳州市",
        150=>"池州市",
        151=>"宣城市",
        152=>"福州市",
        153=>"厦门市",
        154=>"莆田市",
        155=>"三明市",
        156=>"泉州市",
        157=>"漳州市",
        158=>"南平市",
        159=>"龙岩市",
        160=>"宁德市",
        161=>"南昌市",
        162=>"景德镇市",
        163=>"萍乡市",
        164=>"九江市",
        165=>"新余市",
        166=>"鹰潭市",
        167=>"赣州市",
        168=>"吉安市",
        169=>"宜春市",
        170=>"抚州市",
        171=>"上饶市",
        172=>"济南市",
        173=>"青岛市",
        174=>"淄博市",
        175=>"枣庄市",
        176=>"东营市",
        177=>"烟台市",
        178=>"潍坊市",
        179=>"济宁市",
        180=>"泰安市",
        181=>"威海市",
        182=>"日照市",
        183=>"莱芜市",
        184=>"临沂市",
        185=>"德州市",
        186=>"聊城市",
        187=>"滨州市",
        188=>"菏泽市",
        189=>"郑州市",
        190=>"开封市",
        191=>"洛阳市",
        192=>"平顶山市",
        193=>"安阳市",
        194=>"鹤壁市",
        195=>"新乡市",
        196=>"焦作市",
        197=>"济源市",
        198=>"沁阳市",
        199=>"孟州市",
        200=>"濮阳市",
        201=>"许昌市",
        202=>"漯河市",
        203=>"三门峡市",
        204=>"南阳市",
        205=>"商丘市",
        206=>"信阳市",
        207=>"周口市",
        208=>"驻马店市",
        209=>"",
        210=>"武汉市",
        211=>"黄石市",
        212=>"十堰市",
        213=>"宜昌市",
        214=>"襄阳市",
        215=>"鄂州市",
        216=>"荆门市",
        217=>"孝感市",
        218=>"荆州市",
        219=>"黄冈市",
        220=>"咸宁市",
        221=>"随州市",
        222=>"恩施自治州",
        223=>"湖北省辖单位",
        224=>"长沙市",
        225=>"株洲市",
        226=>"湘潭市",
        227=>"衡阳市",
        228=>"邵阳市",
        229=>"岳阳市",
        230=>"常德市",
        231=>"张家界市",
        232=>"益阳市",
        233=>"郴州市",
        234=>"永州市",
        235=>"怀化市",
        236=>"娄底市",
        237=>"湘西自治州",
        238=>"广州市",
        239=>"韶关市",
        240=>"深圳市",
        241=>"珠海市",
        242=>"汕头市",
        243=>"佛山市",
        244=>"江门市",
        245=>"湛江市",
        246=>"茂名市",
        247=>"肇庆市",
        248=>"惠州市",
        249=>"梅州市",
        250=>"汕尾市",
        251=>"河源市",
        252=>"阳江市",
        253=>"清远市",
        254=>"东莞市",
        255=>"中山市",
        256=>"潮州市",
        257=>"揭阳市",
        258=>"云浮市",
        259=>"南宁市",
        260=>"柳州市",
        261=>"桂林市",
        262=>"梧州市",
        263=>"北海市",
        264=>"防城港市",
        265=>"钦州市",
        266=>"贵港市",
        267=>"玉林市",
        268=>"百色市",
        269=>"贺州市",
        270=>"河池市",
        271=>"来宾市",
        272=>"崇左市",
        273=>"海口市",
        274=>"三亚市",
        275=>"海南直辖县",
        276=>"重庆辖区",
        277=>"重庆辖县",
        278=>"成都市",
        279=>"自贡市",
        280=>"攀枝花市",
        281=>"泸州市",
        282=>"德阳市",
        283=>"绵阳市",
        284=>"广元市",
        285=>"遂宁市",
        286=>"内江市",
        287=>"乐山市",
        288=>"南充市",
        289=>"眉山市",
        290=>"宜宾市",
        291=>"广安市",
        292=>"达州市",
        293=>"雅安市",
        294=>"巴中市",
        295=>"资阳市",
        296=>"阿坝自治州",
        297=>"甘孜自治州",
        298=>"凉山自治州",
        299=>"贵阳市",
        300=>"六盘水市",
        301=>"遵义市",
        302=>"安顺市",
        303=>"铜仁地区",
        304=>"黔西南自治州",
        305=>"毕节地区",
        306=>"黔东南自治州",
        307=>"黔南自治州",
        308=>"昆明市",
        309=>"曲靖市",
        310=>"玉溪市",
        311=>"保山市",
        312=>"昭通市",
        313=>"丽江市",
        314=>"思茅市",
        315=>"临沧市",
        316=>"楚雄自治州",
        317=>"红河自治州",
        318=>"文山自治州",
        319=>"西双版纳州",
        320=>"大理自治州",
        321=>"德宏自治州",
        322=>"怒江傈自治州",
        323=>"迪庆自治州",
        324=>"拉萨市",
        325=>"昌都地区",
        326=>"山南地区",
        327=>"日喀则地区",
        328=>"那曲地区",
        329=>"阿里地区",
        330=>"林芝地区",
        331=>"西安市",
        332=>"铜川市",
        333=>"宝鸡市",
        334=>"咸阳市",
        335=>"渭南市",
        336=>"延安市",
        337=>"汉中市",
        338=>"榆林市",
        339=>"安康市",
        340=>"商洛市",
        341=>"兰州市",
        342=>"嘉峪关市",
        343=>"金昌市",
        344=>"白银市",
        345=>"天水市",
        346=>"武威市",
        347=>"张掖市",
        348=>"平凉市",
        349=>"酒泉市",
        350=>"庆阳市",
        351=>"定西市",
        352=>"陇南市",
        353=>"临夏自治州",
        354=>"甘南自治州",
        355=>"西宁市",
        356=>"海东地区",
        357=>"海北自治州",
        358=>"黄南自治州",
        359=>"海南自治州",
        360=>"果洛自治州",
        361=>"玉树自治州",
        362=>"海西自治州",
        363=>"银川市",
        364=>"石嘴山市",
        365=>"吴忠市",
        366=>"固原市",
        367=>"中卫市",
        368=>"乌鲁木齐市",
        369=>"克拉玛依市",
        370=>"吐鲁番地区",
        371=>"哈密地区",
        372=>"昌吉自治州",
        373=>"博尔塔拉州",
        374=>"巴音郭楞州",
        375=>"阿克苏地区",
        376=>"克孜勒苏州喀什地区",
        377=>"和田地区",
        378=>"伊犁自治州塔城地区",
        379=>"阿勒泰地区",
        380=>"新疆省辖单位",
        381=>"台北市",
        382=>"高雄市",
        383=>"基隆市",
        384=>"台中市",
        385=>"台南市",
        386=>"新竹市",
        387=>"嘉义市",
        388=>"香港岛九龙",
        389=>"新界东",
        390=>"新界西",
        391=>"花地玛堂区",
        392=>"圣安多尼堂区",
        393=>"花王堂区",
        394=>"大堂区",
        395=>"望德堂区",
        396=>"风顺堂区",
        397=>"境外地区",
        398=>"其他地区",
    ];

    const HIERARCHY = [
        0=>[35,36],
        1=>[37,38],
        2=>[39,40,41,42,43,44,45,46,47,48,49],
        3=>[50,51,52,53,54,55,56,57,58,59,60],
        4=>[61,62,63,64,65,66,67,68,69,70,71,72],
        5=>[73,74,75,76,77,78,79,80,81,82,83,84,85,86],
        6=>[87,88,89,90,91,92,93,94,95],
        7=>[96,97,98,99,100,101,102,103,104,105,106,107,108],
        8=>[109,110],
        9=>[111,112,113,114,115,116,117,118,119,120,121,122,123],
        10=>[124,125,126,127,128,129,130,131,132,133,134],
        11=>[135,136,137,138,139,140,141,142,143,144,145,146,147,148,149,150,151],
        12=>[152,153,154,155,156,157,158,159,160],
        13=>[161,162,163,164,165,166,167,168,169,170,171],
        14=>[172,173,174,175,176,177,178,179,180,181,182,183,184,185,186,187,188],
        15=>[189,190,191,192,193,194,195,196,197,198,199,200,201,202,203,204,205,206,207,208,209],
        16=>[210,211,212,213,214,215,216,217,218,219,220,221,222,223],
        17=>[224,225,226,227,228,229,230,231,232,233,234,235,236,237],
        18=>[238,239,240,241,242,243,244,245,246,247,248,249,250,251,252,253,254,255,256,257,258],
        19=>[259,260,261,262,263,264,265,266,267,268,269,270,271,272],
        20=>[273,274,275],
        21=>[276,277],
        22=>[278,279,280,281,282,283,284,285,286,287,288,289,290,291,292,293,294,295,296,297,298],
        23=>[299,300,301,302,303,304,305,306,307],
        24=>[308,309,310,311,312,313,314,315,316,317,318,319,320,321,322,323],
        25=>[324,325,326,327,328,329,330],
        26=>[331,332,333,334,335,336,337,338,339,340],
        27=>[341,342,343,344,345,346,347,348,349,350,351,352,353,354],
        28=>[355,356,357,358,359,360,361,362],
        29=>[363,364,365,366,367],
        30=>[368,369,370,371,372,373,374,375,376,377,378,379,380],
        31=>[381,382,383,384,385,386,387],
        32=>[388,389,390],
        33=>[391,392,393,394,395,396],
        34=>[397,398],
    ];

    public static function AllCity(){

        return self::CITY;
    }

    public static function Hierarchy(){

        return self::HIERARCHY;
    }

    /**
     * 根据城市获取 是否有运费
     *
     */
    public static function getCity($city){

       $citys =  array_flip(self::CITY);
        if(isset($citys[$city])){
            $int = $citys[$city];
            $friend = new Freight();
            $all_friend = $friend->getAll();
            if($all_friend){
                foreach($all_friend as $v){
                    $regios = json_decode($v->regions);
                    if(in_array($int,$regios)){
                        return ['price'=>$v->price,'over_price'=>$v->over_price];
                    }
                }
                return false;
            }else{
                return false;
            }
        }

       return false;
    }
}