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
    <label for="inputEmail3" class="col-sm-2 control-label">用户名</label>
    <div class="col-sm-10">
      <input type="text" name="username" class="form-control" id="inputEmail3" placeholder="用户名"  value=" @if (!empty($data)) {{$data['username']}}  @endif">
    </div>
  </div>
  <div class="form-group">
    <label for="inputPassword3" class="col-sm-2 control-label">密码</label>
    <div class="col-sm-10">
      <input type="password" name="password" class="form-control" id="inputPassword3" placeholder=""  >
      <p> @if (!empty($data)) <input type="hidden" name="dataid" id="dataid" value="{{$data['id']}}">  修改密码直接填写，不修改不填写   @else  密码 @endif</p>
    </div>
  </div>

  <div class="form-group">
    <label for="inputEmail3" class="col-sm-2 control-label">电话</label>
    <div class="col-sm-10">
      <input type="text" name="phone" class="form-control" id="inputEmail3" placeholder="电话" value=" @if (!empty($data)) {{$data['phone']}}  @endif">
    </div>
  </div>

  <div class="form-group">
    <label for="inputEmail3" class="col-sm-2 control-label">角色</label>
    <select class="form-control" name="role">
         @if (empty($data)) 
            <option value="0" selected>请选择</option>
         @endif
      @foreach ($list as $key=>$datas )
        @if (empty($data)) 
            <option value="{{$datas['id']}}" >{{$datas['role']}}</option>
        @else
            @if ($datas['id'] == $data['role']  )
                <option value="{{$datas['id']}}" selected>{{$datas['role']}}</option>
            @else
                <option value="{{$datas['id']}}" >{{$datas['role']}}</option>
            @endif
        @endif

      @endforeach
    </select>
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
                // $("#horizontal").serialize()
                if($('#dataid').val()!=undefined){
                    $.ajax({
                    data:{'form':$("#horizontal").serializeJson(),'type':'update'},
                    type:'post',
                    dataType:'json',
                    url:"{{url('admin/addadminuser')}}",
                    success:function(res){
                        //console.log(res);return;
                        if(res.code==1){
                            alert(res.msg);
                            location.reload();
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
                    url:"{{url('admin/addadminuser')}}",
                    success:function(res){
                        if(res.code==1){
                            alert(res.msg);
                            location.reload();
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