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
    <label for="inputEmail3" class="col-sm-2 control-label">角色名</label>
    <div class="col-sm-10">
      <input type="text" name="role" class="form-control" id="inputEmail3" placeholder="角色名"  value=" @if (!empty($data)) {{$data['role']}}  @endif">
    </div>
  </div>

  <div class="form-group">
    <label for="inputEmail3" class="col-sm-2 control-label">状态</label>
        @if (!empty($data))
          <input type="text" hidden name="" id="id" value="{{$id}}">
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



<div class="layui-form-item">
	 <label class="layui-form-label">可用权限</label>
		<div class="layui-input-block">
			<div id="accordion" class="panel-group">
				<div class="panel panel-default">
					@foreach ($list  as $key=> $item)

						<div class="panel-heading" style="background:#f8f8f8">
								<a class="btn btn-link btn-sm pull-right" data-toggle="collapse" data-parent="#accordion" href="#collapse{{$item['rout']}}"><i class="fa fa-angle-down"></i> 展开</a>
								<label class="checkbox-inline">
                    @if (!empty($item['ceshi']))
										 <input type="checkbox" lay-ignore="" id="perm_{{$item['id']}}" checked name="perms" value="{{$item['id']}}" class="perm-all" data-group="{{$item['rout']}}" title="" style="display: inline-block;"> {{$item['menuname']}}
                     @else
                      <input type="checkbox" lay-ignore="" id="perm_{{$item['id']}}" name="perms" value="{{$item['id']}}" class="perm-all" data-group="{{$item['rout']}}" title="" style="display: inline-block;"> {{$item['menuname']}}
                     @endif

								</label>
						</div>


						<div id="collapse{{$item['rout']}}" class="panel-collapse in">
							<div class="panel-body perm-group">
								<span>
									<div class="col-sm-12">
										<label class="checkbox-inline">
											<input type="checkbox" lay-ignore="" name="perms" value="{{$item['id']}}" class="perm-all-item" data-group="{{$item['rout']}}" data-parent="text" title="" style="display: inline-block;">{{$item['menuname']}}  
									 </label>
									</div>
								</span>

								<br>
								@if (!empty($item['items']))

									@foreach ($item['items']  as $keys=> $items)	
                    @if(!empty($data))

                      
                          @if (!empty($items['ceshi']))
                            
                            <span>
                              <div class="col-sm-12">
                                <label class="checkbox-inline">
                              <input type="checkbox" lay-ignore="" checked name="perms" value="{{$items['id']}}" class="perm-item" data-group="{{$item['rout']}}" data-parent="{{$item['rout']}}" data-son="{{$item['rout']}}" title="" style="display: inline-block;">{{$items['menuname']}}
                                 </label>
                              </div>
                            </span>
                          @else
                            <span>
                              <div class="col-sm-12">
                                <label class="checkbox-inline">
                              <input type="checkbox" lay-ignore="" name="perms" value="{{$items['id']}}" class="perm-item" data-group="{{$item['rout']}}" data-parent="{{$item['rout']}}" data-son="{{$item['rout']}}" title="" style="display: inline-block;">{{$items['menuname']}}
                                 </label>
                              </div>
                            </span>

                          @endif

                      

                    @else	
                        <span>
                          <div class="col-sm-12">
                            <label class="checkbox-inline">
                          <input type="checkbox" lay-ignore="" name="perms" value="{{$items['id']}}" class="perm-item" data-group="{{$item['rout']}}" data-parent="{{$item['rout']}}" data-son="{{$item['rout']}}" title="" style="display: inline-block;">{{$items['menuname']}}
                             </label>
                          </div>
                        </span>
                      @endif
								
									@endforeach

								@endif

								<br>										
							</div>
						</div>
					@endforeach
				</div>
			</div>
		</div>
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

<script language="javascript">
    $(function () {
      $('.perm-all').click(function () {
        var checked = $(this).get(0).checked;
        var group = $(this).data('group');

        $(".perm-item[data-group='" + group + "'],.perm-all-item[data-group='" + group + "']").each(function () {
          $(this).get(0).checked = checked;
        })
      })

      $('.perm-all-item').click(function () {
        var checked = $(this).get(0).checked;
        var group = $(this).data('group');
        var parent = $(this).data('parent');
        var son = $(this).data('son');
        var grandson = $(this).data('grandson');
        $(this).parents("span").find(".perm-item").each(function () {
          $(this).get(0).checked = checked;
        });
        group_check(this);

      });

      $('.perm-item').click(function () {
        var group = $(this).data('group');
        var parent = $(this).data('parent');
        var son = $(this).data('son');
        var grandson = $(this).data('grandson');
        var check = false;
        $(this).closest('span').find(".perm-item").each(function () {
          if ($(this).get(0).checked) {
            check = true;
            return false;
          }
        });

        var allitem = $(this).parents("span").find(".perm-all-item");
        if (allitem.length == 1) {
          allitem.get(0).checked = check;
        }
        group_check(this);

      });

      $(".panel-body").find("span").each(function (index, item) {
        if ($(this).find("label").length != 1) {
          $($(this).find("label").get(0)).wrap("<div class='col-sm-2' style='white-space:nowrap;'></div>");
          $($(this).find("label").not($(this).find("label").get(0))).wrapAll("<div class='col-sm-10'></div>");
        }
        else {
          $($(this).find("label").get(0)).wrap("<div class='col-sm-12'></div>");
        }
      });

    });

    function group_check(obj) {
      var check = false;
      $(obj).parents('.perm-group').find(":checkbox").each(function (index, item) {
        if (item.checked) {
          check = true;
        }
      });
      var group = $(obj).eq(0).data('group');
      $(".perm-all[data-group=" + group + "]").get(0).checked = check;
    }

</script>

<script type="text/javascript">
	$("#sub").click(function(){
		
		var  form=$("form").serializeJson();
		 // console.log(form);
		 // console.log(form.perms);
		if(form.perms == undefined ){
			alert('请选择该角色的权限');
		}else if(form.role==' '){
			alert('请输入角色名称');
		}else{
			form.perms=unique1(form.perms);
			
      var ids=$("#id").val();
      form.id=ids;
      
      var type = 'update';

      if(ids==undefined){
        type = 'add';
      }


			$.ajax({
				url:'{{url("admin/addrole")}}',
				data:{form,'type':type},
				type:'post',
				datatype:'json',
				success:function(res){
					console.log(res);
          if(res.code==2){
            alert(res.msg);
            location.reload();
          }else{
            alert(res.msg);
          }
				}

			})

		}
	})

	function unique1(array){
    	var n = []; //一个新的临时数组
	    for(var i = 0; i < array.length; i++){
	       if (n.indexOf(array[i]) == -1) n.push(array[i]);
	    }
	    return n;
	}

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