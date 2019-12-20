<!DOCTYPE html>
<html lang="en">

<head>

    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="description" content="">
    <meta name="author" content="">

    <title></title>

    <!-- Bootstrap Core CSS -->
    <link href="{{asset('page/table/vendor/bootstrap/css/bootstrap.min.css')}}" rel="stylesheet">

    <!-- MetisMenu CSS -->
    <link href="{{asset('page/table/vendor/metisMenu/metisMenu.min.css')}}" rel="stylesheet">

    <!-- DataTables CSS -->
    <link href="{{asset('page/table/vendor/datatables-plugins/dataTables.bootstrap.css')}}" rel="stylesheet">

    <!-- DataTables Responsive CSS -->
    <link href="{{asset('page/table/vendor/datatables-responsive/dataTables.responsive.css')}}" rel="stylesheet">

    <!-- Custom CSS -->
    <link href="{{asset('page/table/dist/css/sb-admin-2.css')}}" rel="stylesheet">

    <!-- Custom Fonts -->
    <link href="{{asset('page/table/vendor/font-awesome/css/font-awesome.min.css')}}" rel="stylesheet" type="text/css">

    <!-- HTML5 Shim and Respond.js IE8 support of HTML5 elements and media queries -->
    <!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
    <!--[if lt IE 9]>
        <script src="https://oss.maxcdn.com/libs/html5shiv/3.7.0/html5shiv.js"></script>
        <script src="https://oss.maxcdn.com/libs/respond.js/1.4.2/respond.min.js"></script>
    <![endif]-->

</head>

<body>

<form class="form-horizontal" id="horizontal" >

 
  <div class="form-group">
    <label for="inputPassword3" class="col-sm-2 control-label">团队名称</label>
    <div class="col-sm-10">
      <input type="text" name="teamname" class="form-control" id="inputEmail3" placeholder="团队名称"  value=" @if (!empty($data)) {{$data['teamname']}}  @endif ">
    </div>
  </div>

  


  <div class="form-group">
    <label for="inputEmail3" class="col-sm-2 control-label">绑定团队开启代理商id</label>
    @if(!empty($data)) 
        <div class="col-sm-10">
          <input type="text"  readonly class="form-control" id="inputEmail3" placeholder="电话" value=" {{$data['name']}}">
          <input type="text" id="id" class="form-control"  placeholder="电话" value=" {{$data['id']}}" style="display: none;">
        </div>
    @else
    <select class="form-control" name="agentid">
         @if (empty($data)) 
            <option value="0" selected>请选择</option>
         @endif
      @foreach ($list as $key=>$datas )
        @if (empty($data)) 
            <option value="{{$datas['id']}}" >{{$datas['name']}}</option>
        @else

            @if ($datas['id'] == $data['agentid']  )
                <option value="{{$datas['id']}}" selected>{{$datas['name']}}</option>
            @else
                <option value="{{$datas['id']}}" >{{$datas['name']}}</option>
            @endif
        @endif

      @endforeach
    </select>
    @endif
  </div>



  <div class="form-group">
    <label for="inputEmail3" class="col-sm-2 control-label">状态</label>
        @if (!empty($data))
            <label class="radio-inline">
              <input type="radio" name="status" @if ($data['status'] == 1) checked  @endif id="inlineRadio1" value="1"> 启用
            </label>
            <label class="radio-inline">
              <input type="radio" name="status" @if ($data['status'] == 0) checked  @endif  id="inlineRadio2" value="0"> 禁用
            </label>
        @else
            <label class="radio-inline">
              <input type="radio" name="status" checked id="inlineRadio1" value="1"> 启用
            </label>
            <label class="radio-inline">
              <input type="radio" name="status" id="inlineRadio2" value="0"> 禁用
            </label>
        @endif
  </div>


  <div class="form-group">
    <div class="col-sm-offset-2 col-sm-10">

      <button type="button"   id="sub"  class="btn btn-default">提交</button>
    </div>
  </div>
</form>


</body>


    <!-- jQuery -->
    <script src="{{asset('page/table/vendor/jquery/jquery.min.js')}}"></script>

    <!-- Bootstrap Core JavaScript -->
    <script src="{{asset('page/table/vendor/bootstrap/js/bootstrap.min.js')}}"></script>

    <!-- Metis Menu Plugin JavaScript -->
    <script src="{{asset('page/table/vendor/metisMenu/metisMenu.min.js')}}"></script>

    <!-- DataTables JavaScript -->
    <script src="{{asset('page/table/vendor/datatables/js/jquery.dataTables.min.js')}}"></script>
    <script src="{{asset('page/table/vendor/datatables-plugins/dataTables.bootstrap.min.js')}}"></script>
    <script src="{{asset('page/table/vendor/datatables-responsive/dataTables.responsive.js')}}"></script>

    <!-- Custom Theme JavaScript -->
    <script src="{{asset('page/table/dist/js/sb-admin-2.js')}}"></script>

    <!-- Page-Level Demo Scripts - Tables - Use for reference -->
    <script>
        $(document).ready(function() {


            $("#sub").click(function(){

                var form=$("#horizontal").serializeJson();
                if(form.teamname==' '){
                    alert("请完善相关信息");return;
                }


                // console.log($("#horizontal").serializeJson());return;
                if($('#id').val()!=undefined){
                    form.id=$('#id').val();
                    $.ajax({
                    data:{'form':form,'type':'update'},
                    type:'post',
                    dataType:'json',
                    url:"{{url('agent/additem')}}",
                    success:function(res){
                        if(res.code==1){
                            alert(res.msg);
                            localhost.reload();
                        }else{
                            alert(res.msg);
                        }
                    }
                })
                }else{
                    $.ajax({
                    data:{'form':$("#horizontal").serializeJson(),'type':'add'},
                    type:'post',
                    dataType:'json',
                    url:"{{url('agent/additem')}}",
                    success:function(res){
                        if(res.code==1){
                            alert(res.msg);
                            localhost.reload();
                        }else{
                            alert(res.msg);
                        }
                    }
                })
                }
                
            })
        })


       
        

       
        $.fn.serializeJson = function() {
            var serializeObj = {};
            var array = this.serializeArray();
            var str = this.serialize();
            $(array).each(
                    function() {
                        if (serializeObj[this.name]) {
                            if ($.isArray(serializeObj[this.name])) {
                                serializeObj[this.name].push(this.value);
                            } else {
                                serializeObj[this.name] = [
                                        serializeObj[this.name], this.value ];
                            }
                        } else {
                            serializeObj[this.name] = this.value;
                        }
                    });
            return serializeObj;
        };

    </script>
</html>