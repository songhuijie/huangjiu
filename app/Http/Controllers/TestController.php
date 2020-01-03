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
use App\Services\CityServices;
use App\Services\CourierBirdService;
use App\Services\MapServices;
use App\Services\WePushService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Redis;

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
        $id = $request->get('id',1);
        $data = WePushService::send_notice($id);
        dd($data);
    }

    public function test(){
        Redis::get('access_token');
        dd('删除 access_token');


        $v = 0;
        $data = bcmul(0,$v);
        dd($data);
        $order_total_price = "0.01";
        $freight = 0.02;
        $money = (float) bcadd($order_total_price,$freight,2);
        dd($money);
//        $city = CityServices::getCity('其他地区');
//
//        dd($city);
        $array = [
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
        ];
        $array =[
            0=>"北京市 北京辖区 北京辖县",
            1=>"天津市 天津辖区 天津辖县",
            2=>"河北省 石家庄市 唐山市 秦皇岛市 邯郸市 邢台市 保定市 张家口市 承德市 沧州市 廊坊市 衡水市",
            3=>"山西省 太原市 大同市 阳泉市 长治市 晋城市 朔州市 晋中市 运城市 忻州市 临汾市 吕梁市",
            4=>"内蒙古自治区 呼和浩特市 包头市 乌海市 赤峰市 通辽市 鄂尔多斯市 呼伦贝尔市 巴彦淖尔市 乌兰察布市 兴安盟 锡林郭勒盟 阿拉善盟",
            5=>"辽宁省 沈阳市 大连市 鞍山市 抚顺市 本溪市 丹东市 锦州市 营口市 阜新市 辽阳市 盘锦市 铁岭市 朝阳市 葫芦岛市",
            6=>"吉林省 长春市 吉林市 四平市 辽源市 通化市 白山市 松原市 白城市 延边自治州",
            7=>"黑龙江省 哈尔滨市 齐齐哈尔市 鸡西市 鹤岗市 双鸭山市 大庆市 伊春市 佳木斯市 七台河市 牡丹江市 黑河市 绥化市 大兴安岭地区",
            8=>"上海市 上海辖区 上海辖县",
            9=>"江苏省 南京市 无锡市 徐州市 常州市 苏州市 南通市 连云港市 淮安市 盐城市 扬州市 镇江市 泰州市 宿迁市",
            10=>"浙江省 杭州市 宁波市 温州市 嘉兴市 湖州市 绍兴市 金华市 衢州市 舟山市 台州市 丽水市",
            11=>"安徽省 合肥市 芜湖市 蚌埠市 淮南市 马鞍山市 淮北市 铜陵市 安庆市 黄山市 滁州市 阜阳市 宿州市 巢湖市 六安市 亳州市 池州市 宣城市",
            12=>"福建省 福州市 厦门市 莆田市 三明市 泉州市 漳州市 南平市 龙岩市 宁德市",
            13=>"江西省 南昌市 景德镇市 萍乡市 九江市 新余市 鹰潭市 赣州市 吉安市 宜春市 抚州市 上饶市",
            14=>"山东省 济南市 青岛市 淄博市 枣庄市 东营市 烟台市 潍坊市 济宁市 泰安市 威海市 日照市 莱芜市 临沂市 德州市 聊城市 滨州市 菏泽市",
            15=>"河南省 郑州市 开封市 洛阳市 平顶山市 安阳市 鹤壁市 新乡市 焦作市 济源市 沁阳市 孟州市 濮阳市 许昌市 漯河市 三门峡市 南阳市 商丘市 信阳市 周口市 驻马店市 ",
            16=>"湖北省 武汉市 黄石市 十堰市 宜昌市 襄阳市 鄂州市 荆门市 孝感市 荆州市 黄冈市 咸宁市 随州市 恩施自治州 湖北省辖单位",
            17=>"湖南省 长沙市 株洲市 湘潭市 衡阳市 邵阳市 岳阳市 常德市 张家界市 益阳市 郴州市 永州市 怀化市 娄底市 湘西自治州",
            18=>"广东省 广州市 韶关市 深圳市 珠海市 汕头市 佛山市 江门市 湛江市 茂名市 肇庆市 惠州市 梅州市 汕尾市 河源市 阳江市 清远市 东莞市 中山市 潮州市 揭阳市 云浮市",
            19=>"广西壮族自治区 南宁市 柳州市 桂林市 梧州市 北海市 防城港市 钦州市 贵港市 玉林市 百色市 贺州市 河池市 来宾市 崇左市",
            20=>"海南省 海口市 三亚市 海南直辖县",
            21=>"重庆市 重庆辖区 重庆辖县",
            22=>"四川省 成都市 自贡市 攀枝花市 泸州市 德阳市 绵阳市 广元市 遂宁市 内江市 乐山市 南充市 眉山市 宜宾市 广安市 达州市 雅安市 巴中市 资阳市 阿坝自治州 甘孜自治州 凉山自治州",
            23=>"贵州省 贵阳市 六盘水市 遵义市 安顺市 铜仁地区 黔西南自治州 毕节地区 黔东南自治州 黔南自治州",
            24=>"云南省 昆明市 曲靖市 玉溪市 保山市 昭通市 丽江市 思茅市 临沧市 楚雄自治州 红河自治州 文山自治州 西双版纳州 大理自治州 德宏自治州 怒江傈自治州 迪庆自治州",
            25=>"西藏区 拉萨市 昌都地区 山南地区 日喀则地区 那曲地区 阿里地区 林芝地区",
            26=>"陕西省 西安市 铜川市 宝鸡市 咸阳市 渭南市 延安市 汉中市 榆林市 安康市 商洛市",
            27=>"甘肃省 兰州市 嘉峪关市 金昌市 白银市 天水市 武威市 张掖市 平凉市 酒泉市 庆阳市 定西市 陇南市 临夏自治州 甘南自治州",
            28=>"青海省 西宁市 海东地区 海北自治州 黄南自治州 海南自治州 果洛自治州 玉树自治州 海西自治州",
            29=>"宁夏区 银川市 石嘴山市 吴忠市 固原市 中卫市",
            30=>"新疆区 乌鲁木齐市 克拉玛依市 吐鲁番地区 哈密地区 昌吉自治州 博尔塔拉州 巴音郭楞州 阿克苏地区 克孜勒苏州喀什地区 和田地区 伊犁自治州塔城地区 阿勒泰地区 新疆省辖单位",
            31=>"台湾省 台北市 高雄市 基隆市 台中市 台南市 新竹市 嘉义市",
            32=>"香港特区 香港岛九龙 新界东 新界西",
            33=>"澳门特区 花地玛堂区 圣安多尼堂区 花王堂区 大堂区 望德堂区 风顺堂区",
            34=>"境外 境外地区 其他地区",
        ];



        $count = 35;
        foreach($array as $k=>$v){
            $a = explode(' ',$v);
            unset($a[0]);
//            $html = '';
//            foreach($a as $key=>$value){
//                $html .= $count.',';
//                $count++;
//            }
            foreach($a as $key=>$value){
                echo $count .'=>"'.$value.'",';
                echo "</br>";
                $count++;
            }
//            $html= substr($html, 0, -1);
//            echo $k.'=>['.$html.'],';
//            echo "<br/>";

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