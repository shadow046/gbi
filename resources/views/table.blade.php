<!DOCTYPE html>
<html>
    <head>
        <meta charset="utf-8" />
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <title>GBI DATABASE</title>
        <meta name="viewport" content="width=device-width, initial-scale=1">
    </head>
    <body>
        <div class="table-responsive container-fluid">
            <table class="table-hover table gbiTable" id="gbiTable" style="width:100%;border: 1px solid black;border-collapse: collapse;">
                <thead style="border: 1px solid black;border-collapse: collapse;">
                    <tr>
                        @php
                        @endphp
                        @foreach ( $headers as $head => $header )
                            @php
                                $i = $head-2 
                            @endphp
                                @if ($header->column != "TaskId")
                                    @if ($header->column != "FormId")
                                        <th style="border: 1px solid black;border-collapse: collapse;" onclick="sortTable({{$i}})">&nbsp;&nbsp;{{ $header->column }}&nbsp;&nbsp;</th>
                                    @endif
                                @endif
                                
                        @endforeach
                    </tr>
                </thead>
                <tbody style="border: 1px solid black;border-collapse: collapse;">
                    @foreach ( $tickets as $ticket )
                        <tr>
                            @foreach ( $headers as $head)
                                @php
                                    $i = $head->column
                                @endphp
                                @if ($i != "TaskId")
                                    @if ($i != "FormId")
                                        <td style="font-size:70%;border: 1px solid black;border-collapse: collapse;">{{ $ticket->$i }}</td>
                                    @endif
                                @endif
                            @endforeach
                        </tr>
                    @endforeach
                </tbody>
            </table>
            <br>
        </div>
            <div class="d-flex justify-content-center">
                {!! $tickets->links() !!}
            </div>
    </body>
    <script>
        function sortTable(n) {
        var table, rows, switching, i, x, y, shouldSwitch, dir, switchcount = 0;
        table = document.getElementById("gbiTable");
        switching = true;
        // Set the sorting direction to ascending:
        dir = "asc";
        /* Make a loop that will continue until
        no switching has been done: */
        while (switching) {
            // Start by saying: no switching is done:
            switching = false;
            rows = table.rows;
            /* Loop through all table rows (except the
            first, which contains table headers): */
            for (i = 1; i < (rows.length - 1); i++) {
            // Start by saying there should be no switching:
            shouldSwitch = false;
            /* Get the two elements you want to compare,
            one from current row and one from the next: */
            x = rows[i].getElementsByTagName("TD")[n];
            y = rows[i + 1].getElementsByTagName("TD")[n];
            /* Check if the two rows should switch place,
            based on the direction, asc or desc: */
            if (dir == "asc") {
                if (x.innerHTML.toLowerCase() > y.innerHTML.toLowerCase()) {
                // If so, mark as a switch and break the loop:
                shouldSwitch = true;
                break;
                }
            } else if (dir == "desc") {
                if (x.innerHTML.toLowerCase() < y.innerHTML.toLowerCase()) {
                // If so, mark as a switch and break the loop:
                shouldSwitch = true;
                break;
                }
            }
            }
            if (shouldSwitch) {
            /* If a switch has been marked, make the switch
            and mark that a switch has been done: */
            rows[i].parentNode.insertBefore(rows[i + 1], rows[i]);
            switching = true;
            // Each time a switch is done, increase this count by 1:
            switchcount ++;
            } else {
            /* If no switching has been done AND the direction is "asc",
            set the direction to "desc" and run the while loop again. */
            if (switchcount == 0 && dir == "asc") {
                dir = "desc";
                switching = true;
            }
            }
        }
        }
        </script>
</html>
