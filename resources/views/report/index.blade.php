<html>

   <head>
       <title>Reports - List</title>

       <style type="text/css">
       table, th, td {
            border: 1px solid black;
         }
         th,td{
             padding: 20px;
         }
    </style>
    </head>

<body>
    <h3>Reports List</h3>
   
    <hr>
    <table style="width:50%">
         <thead>
             <th>Description</th>
             <th>Photo</th>
         </thead>

         <tbody>
            @foreach($reports as $report)
            <tr>
                <td>{{ $report->description }}</td>
                <td>
                    <a target="_blank" href="{{ asset('storage/'.$report->photo) }}"> {{ $report->photo }} </a>
                    <img src="{{ asset('storage/'.$report->photo) }}" width="200" height="200">
                </td>
            </tr> 
            @endforeach
         </tbody>
    </table>
</body>

</html>