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
    <div id="wrapper">
        <div id="page-wrapper">
            <div class="row">
                <div class="col-lg-12">
                    <h1 class="page-header">当前机构推广人员</h1>
                </div>
                <!-- /.col-lg-12 -->
            </div>
            <!-- /.row -->
            <div class="row">
                <div class="col-lg-12">
                    <div class="panel panel-default">
                        <div class="panel-heading clearfix">
                            推广情况
                            @if (!empty($id))
                            <input type="text" hidden name="id" id='meid' value="{{$id}}">
                            <a href="{{url('mechanism/pastperiod')}}?id={{$id}}" class="btn btn-success pull-right"  >查看往期推广与佣金</a>
                            @endif
                        </div>
                        <!-- /.panel-heading -->
                        <div class="panel-body">

                            <table width="100%" class="table table-striped table-bordered table-hover" id="dataTables-example">
                                <thead>
                                    <tr>
                                        <th>id</th>
                                        <th>推广人员</th>
                                        <!-- <th>头像</th> -->
                                        <th>性别</th>
                                        <th>年龄</th>
                                        <th>电话</th>
                                        @if (empty($id))
                                            <th>应获得推广佣金</th>
                                            <th>佣金状态</th>
                                            <th>审核时间</th>
                                        @else
                                            <th>操作</th>
                                        @endif
                                    </tr>
                                </thead>

                                <tbody>
                                    @foreach ($list as $key=>$item)
                                    <tr class="odd gradeX">
                                        <td>{{$item['id']}}</td>
                                        <td>{{$item['name']}}</td>
                                        <!-- <td><img src=" config('url')></td> -->
                                        <td>
                                            @if($item['sex']==1)
                                                男
                                            @else
                                                女
                                            @endif
                                        </td>
                                        <td>{{$item['age']}}</td>
                                        <td>{{$item['phone']}}</td>

                                       @if (empty($id))
                                            <td>{{$item['percentage']}}</td>
                                            <td>
                                                @if($item['status']==2)
                                                    推广中
                                                @elseif($item['status']==1)
                                                    已获得佣金
                                                @else
                                                    未获得佣金
                                                @endif
                                            </td>
                                            <td>{{ date('Y-m-d H:i:s',$item['time'])}}</td>
                                        @endif
                                      

                                        <td class="center" >
                                            @if (!empty($id))
                                            <a  class="btn btn-success" href="{{URL('mechanism/settlement')}}?id={{$item['id']}}&mechid={{$id}}&status=1"  >
                                                结算
                                            </a>

                                            <a  class="btn btn-success" href="{{URL('mechanism/settlement')}}?id={{$item['id']}}&mechid={{$id}}&status=0"  >
                                                不结算
                                            </a>
                                            @endif
                                            
                                        </td>
                                        
                                    </tr>
                                   @endforeach
                                   
                                </tbody>
                            </table>
                        </div>
                        <!-- /.panel-body -->
                    </div>
                    <!-- /.panel -->
                </div>
                <!-- /.col-lg-12 -->
            </div>

        </div>
        <!-- /#page-wrapper -->

    </div>
    <!-- /#wrapper -->

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
        $('#dataTables-example').DataTable({
            responsive: true
        });
    });

    var id=$("#meid").val();
    function huode(obj){
        var status=obj.value;
        location.href = "{{url('mechanism/extension')}}?type=edit&id="+id+"&status="+status;
    }
    function nothuode(obj){
        var status=obj.value;
        location.href = "{{url('mechanism/extension')}}?type=edit&id="+id+"&status="+status;
    }

    function btnsuc(obj){
        $.ajax({
            url:"{{url('mechanism/details')}}",
            data:{'type':'edit','id':obj.value},
            dataType:'json',
            type:'post',
            success:function(res){
                // console.log(res);
                if(res.code==1){
                    alert(res.msg);
                    location.reload();
                }else{
                    alert(res.msg);
                }
            }
        })
    }

    /*function edit(obj){
        var id=obj.id;
        location.href = "{{url('mechanism/adddetails')}}?type=edit&id="+id;
    }*/

    </script>

</body>

</html>
