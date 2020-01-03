<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2019/12/17
 * Time: 18:03
 */
namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Model\About;
use App\Model\Agent;
use App\Model\Asset;
use App\Model\Collection;
use App\Model\Friend;
use App\Model\Goods;
use App\Model\IncomeDetails;
use App\Model\Order;
use App\Model\User;
use App\Services\AlibabaSms;
use App\Services\CourierBirdService;
use App\Services\MapServices;
use Illuminate\Http\Request;

class TestController extends Controller{

    private $friend;
    private $user;
    private $agent;
    public function __construct(Friend $friend,User $user,Agent $agent)
    {
        $this->friend = $friend;
        $this->user = $user;
        $this->agent = $agent;
    }


    /**
     * 数组去重
     */
    public static function array_unset_tt($arr,$key){
        //建立一个目标数组
        $res = array();
        foreach ($arr as $value) {
            //查看有没有重复项
            if(isset($res[$value[$key]])){
                unset($value[$key]);  //有：销毁
            }else{
                $res[$value[$key]] = $value;
            }
        }
        return $res;
    }

    public function push(Request $request){
        //https://huangjiu.xcooteam.cn/message/push
        $signature = $_GET["signature"];
        $timestamp = $_GET["timestamp"];
        $nonce = $_GET["nonce"];

        $token = 'huangjiushangcheng';
        $tmpArr = array($token, $timestamp, $nonce);
        sort($tmpArr, SORT_STRING);
        $tmpStr = implode( $tmpArr );
        $tmpStr = sha1( $tmpStr );


        if ($tmpStr == $signature ) {
            return True;
        } else {
            return False;
        }
    }

    public function test(){

        $array =[
            0=>"北京市北京辖区北京辖县",
            1=>"天津市天津辖区天津辖县",
            2=>"河北省石家庄市唐山市秦皇岛市邯郸市邢台市保定市张家口市承德市沧州市廊坊市衡水市",
            3=>"山西省太原市大同市阳泉市长治市晋城市朔州市晋中市运城市忻州市临汾市吕梁市",
            4=>"内蒙古自治区呼和浩特市包头市乌海市赤峰市通辽市鄂尔多斯市呼伦贝尔市巴彦淖尔市乌兰察布市兴安盟锡林郭勒盟阿拉善盟",
            5=>"辽宁省沈阳市大连市鞍山市抚顺市本溪市丹东市锦州市营口市阜新市辽阳市盘锦市铁岭市朝阳市葫芦岛市",
            6=>"吉林省长春市吉林市四平市辽源市通化市白山市松原市白城市延边自治州",
            7=>"黑龙江省哈尔滨市齐齐哈尔市鸡西市鹤岗市双鸭山市大庆市伊春市佳木斯市七台河市牡丹江市黑河市绥化市大兴安岭地区",
            8=>"上海市上海辖区上海辖县",
            9=>"江苏省南京市无锡市徐州市常州市苏州市南通市连云港市淮安市盐城市扬州市镇江市泰州市宿迁市",
            10=>"浙江省杭州市宁波市温州市嘉兴市湖州市绍兴市金华市衢州市舟山市台州市丽水市",
            11=>"安徽省合肥市芜湖市蚌埠市淮南市马鞍山市淮北市铜陵市安庆市黄山市滁州市阜阳市宿州市巢湖市六安市亳州市池州市宣城市",
            12=>"福建省福州市厦门市莆田市三明市泉州市漳州市南平市龙岩市宁德市",
            13=>"江西省南昌市景德镇市萍乡市九江市新余市鹰潭市赣州市吉安市宜春市抚州市上饶市",
            14=>"山东省济南市青岛市淄博市枣庄市东营市烟台市潍坊市济宁市泰安市威海市日照市莱芜市临沂市德州市聊城市滨州市菏泽市",
            15=>"河南省郑州市开封市洛阳市平顶山市安阳市鹤壁市新乡市焦作市济源市沁阳市孟州市濮阳市许昌市漯河市三门峡市南阳市商丘市信阳市周口市驻马店市",
            16=>"湖北省武汉市黄石市十堰市宜昌市襄阳市鄂州市荆门市孝感市荆州市黄冈市咸宁市随州市恩施自治州湖北省辖单位",
            17=>"湖南省长沙市株洲市湘潭市衡阳市邵阳市岳阳市常德市张家界市益阳市郴州市永州市怀化市娄底市湘西自治州",
            18=>"广东省广州市韶关市深圳市珠海市汕头市佛山市江门市湛江市茂名市肇庆市惠州市梅州市汕尾市河源市阳江市清远市东莞市中山市潮州市揭阳市云浮市",
            19=>"广西壮族自治区南宁市柳州市桂林市梧州市北海市防城港市钦州市贵港市玉林市百色市贺州市河池市来宾市崇左市",
            20=>"海南省海口市三亚市海南直辖县",
            21=>"重庆市重庆辖区重庆辖县",
            22=>"四川省成都市自贡市攀枝花市泸州市德阳市绵阳市广元市遂宁市内江市乐山市南充市眉山市宜宾市广安市达州市雅安市巴中市资阳市阿坝自治州甘孜自治州凉山自治州",
            23=>"贵州省贵阳市六盘水市遵义市安顺市铜仁地区黔西南自治州毕节地区黔东南自治州黔南自治州",
            24=>"云南省昆明市曲靖市玉溪市保山市昭通市丽江市思茅市临沧市楚雄自治州红河自治州文山自治州西双版纳州大理自治州德宏自治州怒江傈自治州迪庆自治州",
            25=>"西藏区拉萨市昌都地区山南地区日喀则地区那曲地区阿里地区林芝地区",
            26=>"陕西省西安市铜川市宝鸡市咸阳市渭南市延安市汉中市榆林市安康市商洛市",
            27=>"甘肃省兰州市嘉峪关市金昌市白银市天水市武威市张掖市平凉市酒泉市庆阳市定西市陇南市临夏自治州甘南自治州",
            28=>"青海省西宁市海东地区海北自治州黄南自治州海南自治州果洛自治州玉树自治州海西自治州",
            29=>"宁夏区银川市石嘴山市吴忠市固原市中卫市",
            30=>"新疆区乌鲁木齐市克拉玛依市吐鲁番地区哈密地区昌吉自治州博尔塔拉州巴音郭楞州阿克苏地区克孜勒苏州喀什地区和田地区伊犁自治州塔城地区阿勒泰地区新疆省辖单位",
            31=>"台湾省台北市高雄市基隆市台中市台南市新竹市嘉义市",
            32=>"香港特区香港岛九龙新界东新界西",
            33=>"澳门特区花地玛堂区圣安多尼堂区花王堂区大堂区望德堂区风顺堂区",
            34=>"境外境外地区其他地区",
        ];
        $array = [
            0=>"北京市北京辖区北京辖县",
            1=>"天津市天津辖区天津辖县",
            2=>"河北省石家庄市唐山市秦皇岛市邯郸市邢台市保定市张家口市承德市沧州市廊坊市衡水市",
            3=>"山西省太原市大同市阳泉市长治市晋城市朔州市晋中市运城市忻州市临汾市吕梁市",
            4=>"内蒙古自治区呼和浩特市包头市乌海市赤峰市通辽市鄂尔多斯市呼伦贝尔市巴彦淖尔市乌兰察布市兴安盟锡林郭勒盟阿拉善盟",
            5=>"辽宁省沈阳市大连市鞍山市抚顺市本溪市丹东市锦州市营口市阜新市辽阳市盘锦市铁岭市朝阳市葫芦岛市",
            6=>"吉林省长春市吉林市四平市辽源市通化市白山市松原市白城市延边自治州",
            7=>"黑龙江省哈尔滨市齐齐哈尔市鸡西市鹤岗市双鸭山市大庆市伊春市佳木斯市七台河市牡丹江市黑河市绥化市大兴安岭地区",
            8=>"上海市上海辖区上海辖县",
            9=>"江苏省南京市无锡市徐州市常州市苏州市南通市连云港市淮安市盐城市扬州市镇江市泰州市宿迁市",
            10=>"浙江省杭州市宁波市温州市嘉兴市湖州市绍兴市金华市衢州市舟山市台州市丽水市",
            11=>"安徽省合肥市芜湖市蚌埠市淮南市马鞍山市淮北市铜陵市安庆市黄山市滁州市阜阳市宿州市巢湖市六安市亳州市池州市宣城市",
            12=>"福建省福州市厦门市莆田市三明市泉州市漳州市南平市龙岩市宁德市",
            13=>"江西省南昌市景德镇市萍乡市九江市新余市鹰潭市赣州市吉安市宜春市抚州市上饶市",
            14=>"山东省济南市青岛市淄博市枣庄市东营市烟台市潍坊市济宁市泰安市威海市日照市莱芜市临沂市德州市聊城市滨州市菏泽市",
            15=>"河南省郑州市开封市洛阳市平顶山市安阳市鹤壁市新乡市焦作市济源市沁阳市孟州市濮阳市许昌市漯河市三门峡市南阳市商丘市信阳市周口市驻马店市",
            16=>"湖北省武汉市黄石市十堰市宜昌市襄阳市鄂州市荆门市孝感市荆州市黄冈市咸宁市随州市恩施自治州湖北省辖单位",
            17=>"湖南省长沙市株洲市湘潭市衡阳市邵阳市岳阳市常德市张家界市益阳市郴州市永州市怀化市娄底市湘西自治州",
            18=>"广东省广州市韶关市深圳市珠海市汕头市佛山市江门市湛江市茂名市肇庆市惠州市梅州市汕尾市河源市阳江市清远市东莞市中山市潮州市揭阳市云浮市",
            19=>"广西壮族自治区南宁市柳州市桂林市梧州市北海市防城港市钦州市贵港市玉林市百色市贺州市河池市来宾市崇左市",
            20=>"海南省海口市三亚市海南直辖县",
            21=>"重庆市重庆辖区重庆辖县",
            22=>"四川省成都市自贡市攀枝花市泸州市德阳市绵阳市广元市遂宁市内江市乐山市南充市眉山市宜宾市广安市达州市雅安市巴中市资阳市阿坝自治州甘孜自治州凉山自治州",
            23=>"贵州省贵阳市六盘水市遵义市安顺市铜仁地区黔西南自治州毕节地区黔东南自治州黔南自治州",
            24=>"云南省昆明市曲靖市玉溪市保山市昭通市丽江市思茅市临沧市楚雄自治州红河自治州文山自治州西双版纳州大理自治州德宏自治州怒江傈自治州迪庆自治州",
            25=>"西藏区拉萨市昌都地区山南地区日喀则地区那曲地区阿里地区林芝地区",
            26=>"陕西省西安市铜川市宝鸡市咸阳市渭南市延安市汉中市榆林市安康市商洛市",
            27=>"甘肃省兰州市嘉峪关市金昌市白银市天水市武威市张掖市平凉市酒泉市庆阳市定西市陇南市临夏自治州甘南自治州",
            28=>"青海省西宁市海东地区海北自治州黄南自治州海南自治州果洛自治州玉树自治州海西自治州",
            29=>"宁夏区银川市石嘴山市吴忠市固原市中卫市",
            30=>"新疆区乌鲁木齐市克拉玛依市吐鲁番地区哈密地区昌吉自治州博尔塔拉州巴音郭楞州阿克苏地区克孜勒苏州喀什地区和田地区伊犁自治州塔城地区阿勒泰地区新疆省辖单位",
            31=>"台湾省台北市高雄市基隆市台中市台南市新竹市嘉义市",
            32=>"香港特区香港岛九龙新界东新界西",
            33=>"澳门特区花地玛堂区圣安多尼堂区花王堂区大堂区望德堂区风顺堂区",
            34=>"境外境外地区其他地区",
        ];

        foreach($array as $k=>$v){
            dd($v);
        }
        dd(1);
        $friend = $this->friend->LowerLevelOne(61);

        $number = [];
        if($friend){
            foreach($friend as $k=>$v){
                dump($v);
                if($v->is_delivery == 1){
                    dump('给他发送');
                    $user = $this->user->find($v->parent_id);
                    if($user && !empty($user->phone_number)){
                        $number[] = $user->phone_number;

                    }
                }
            }
            if($number){
                $new_number = array_unique($number);
                dd($number,1,$new_number);
            }

        }
        dd($number);

        $select = ['user_nickname','user_img','sex','created_at'];
        $user_id = 2;
        $lower = $this->friend->LowerLevel($user_id);

        $lower = array_values(self::array_unset_tt($lower,'parent_id'));

        foreach($lower as $k=>$v){
            if($v['user_id'] == 0){
                unset($lower[$k]);
            }else{
                $lower[$k]['user_info'] = $this->user->select($select)->find($v['user_id']);
                $lower[$k]['created_at'] = $lower[$k]['user_info']->created_at;
                $lower[$k]['count'] = $this->friend->LowerCount($v['user_id']);
                $current = $this->friend->CurrentLevel($v['user_id']);
                $agent = $this->agent->getByUserID($v['user_id'],1);
                if($agent){
                    $lower[$k]['user_status'] = 1;
                }else{
                    $lower[$k]['user_status'] = isset($current['status'])?$current['status']:0;
                }
                $lower[$k]['is_delivery'] = isset($current['is_delivery'])?$current['is_delivery']:0;

                $lower[$k]['contribution_amount'] = $this->friend->Contribution($v['user_id'],$user_id);
            }

        }


        dd($lower);
        $lng = '39.984154';
        $lat = '116.307490';
       $result = MapServices::get_address($lng,$lat);
       dd($result);



        $a = "1,概况,home,index/default,0,statistics,0,1
2,会员,set,member/index,0,member,0,1
3,订单,template,order/index,0,order,0,1
4,商品,component,label/index,0,label,0,1
5,推荐,app,recommend/index,0,recommend,0,1
6,设置,unlink,config/index,0,setup,0,1
7,详细概况,,index/default,1,statistics.list,0,1
8,会员列表,,member/index,2,member.list,0,1
9,订单列,,order/order,3,order.list,0,1
10,推荐列表,,recommend/index,5,recommend.list,0,1
11,程序配置,,config/index,6,setup.list,0,1
12,商品类型,,label/index,4,label.list,0,1
13,代理,notice,agent/index,0,agent,0,1
30,地址,voice,address/index,0,comment,0,1
31,地址管理,,address/index,30,address.list,0,1
32,权限管理,notice,admin/admin,0,admin,0,1
33,管理员,,admin/admin,32,admin.list,0,1
37,商品列表,,goods/index,4,label.destination,0,1
39,关于,about,about/about,0,about.about,0,1
40,关于,,about/about,39,about.about,0,1
42,代理审核,,agent/index,13,agent.index,0,1
43,提现,notice,withdraw/index,0,withdraw.index,0,1
44,提现记录,,withdraw/index,43,withdraw.index,0,1";
        $b = explode("\n",$a);
        $datas = [];
        $data = [];
        foreach($b as $k=>$v){

            echo '[';
            echo "<br/>";
            echo "'id'=>'".explode(',',$v)[0]."',";echo "<br/>";
            echo "'menuname'=>'".explode(',',$v)[1]."',";echo "<br/>";
            echo "'icon'=>'".explode(',',$v)[2]."',";echo "<br/>";
            echo "'url'=>'".explode(',',$v)[3]."',";echo "<br/>";
            echo "'pid'=>'".explode(',',$v)[4]."',";echo "<br/>";
            echo "'rout'=>'".explode(',',$v)[5]."',";echo "<br/>";
            echo "'time'=>'".explode(',',$v)[6]."',";echo "<br/>";
            echo "'status'=>'".explode(',',$v)[7]."',";echo "<br/>";
            echo '],';echo "<br/>";
        }

        dd(1);
        $asset  = new Asset();
        $income  = new IncomeDetails();

        $amount = $income->getAmount(1);
        if($amount){
            dd('有');
        }else{
            dd('没');
        }
//        $sum = $asset->sum('royalty_balance');
        dd($amount,$amount == null);
        $collect = new Agent();

        $all =[
            "user_name"=>"杰大哥",
            "iphone"=>"17880952663",
            "city"=>"四川省成都市新都区",
            "address"=>"斑竹园",
            "user_id"=>1,
            "lng"=>104.061378,
            "lat"=>30.81497
        ];
        $user_id = 1;
        $result = $collect->insertAgent($all,$user_id);
        dd($result);
//        $goods = new Goods();
//
//        $goods->updateStock(9,2,1);
//        dd(1);
        return   $this->response( $this->initResponse());
    }
}