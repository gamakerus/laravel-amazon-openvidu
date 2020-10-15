<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
    <style>
        .h4, h4 {
            font-size: 1.125rem;
            font-weight: 300;
        }
        .h3, .h4, h3, h4 {
            line-height: 1.4em;
        }
        .h1, .h2, .h3, .h4, body, h1, h2, h3, h4, h5, h6 {
            font-family: Roboto, Helvetica, Arial, sans-serif;
            font-weight: 300;
            line-height: 1.5em;
        }
        .table {
            width: 660px!important;
            max-width: 100%;
            margin-bottom: 1rem;
            background-color: transparent;
        }
        .text-primary {
            color: #4169e1!important;
        }
        .table tbody tr td{
            text-align:center;
        }
        .text-center{
            text-align:center;
        }
        .provider_active{
            color:#4caf50;
        }
        .provider_deactive{
            color:#ef5350;
        }
        .table tbody tr td{
            width:100px;
        }
    </style>
</head>
<body>
    <img src = "{{asset('adminassets/img/logo.png') }}" width = "200px" />
    <h1 class = "text-center">{{$heading}}</h1>
    <div class="card">
        <table class="table">
            <thead class="text-primary">
                <th>Joined Date</th>
                <th>Full Name</th>
                <th>Email</th>
                <th>Phone</th>
                <th>Verified</th>
                <th>Status</th>
            </thead>
            <tbody>
                <tr><td></td><td></td><td></td><td></td><td></td><td></td></tr>
                @foreach($users as $users_array)
                <tr>
                    <td>{{date("D, M j Y",strtotime($users_array->created_at))}}</td>
                    <td>{{$users_array->fname." ".$users_array->lname}}</td>
                    <td>{{$users_array->email}}</td>
                    <td>{{$users_array->phone}}</td>
                    <td>
                        <?php if($users_array->status == 1): ?>
                            <span class = 'provider_active'>Y</span>
                        <?php elseif($users_array->status == 0): ?>
                            <span class = 'provider_deactive'>N</span>
                        <?php endif ?>
                    </td>
                    <td>
                        <?php if($users_array->allowed == 1): ?>
                            <span class = 'provider_active'>Y</span>
                        <?php elseif($users_array->allowed == 0): ?>
                            <span class = 'provider_deactive'>N</span>
                        <?php endif ?>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</body>
</html>