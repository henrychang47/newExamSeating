<?php
    if(isset($_GET["examID"])){
        $examID = $_GET["examID"];
    }else if (isset($_POST["examID"])){
        $examID = $_POST["examID"];
    }
?>
<html>

<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8">
    <title>監考頁面</title>

    <!-- Sets the basepath for the library if not in same directory -->
    <script type="text/javascript">
        mxBasePath = './src';
    </script>

    <!-- Loads and initializes the library -->
    <script type="text/javascript" src="./src/mxClient.js"></script>

    <!-- functions -->
    <script type="text/javascript" src="functions.js"></script>
    <!-- Example code -->
    <script type="text/javascript">
        function main(container) {
            // Checks if the browser is supported
            if (!mxClient.isBrowserSupported()) {
                // Displays an error message if the browser is not supported.
                mxUtils.error('Browser is not supported!', 200, false);
            } else {
                window.examID = <?php echo $examID ?>;
                startDataUpdate();
                // Creates the graph inside the given container
                window.graph = new mxGraph(container);

                //graph.setEnabled(false);//禁止任何與graph的互動
                graph.setPanning(true);
                graph.setCellsLocked(true)

                graph.panningHandler.useLeftButtonForPanning = true; //左鍵可平移整張圖

                new mxCellTracker(graph, '#FF0000'); //滑鼠停留時發亮

                graph.isHtmlLabel = function(cell) {
                    return true;
                };

                graph.getModel().beginUpdate();
                try {
                    seatingID = 1;//<?php echo $_POST["examID"] ?>;
                    console.log(seatingID);
                    read(graph, seatingID + '.xml');
                } finally {
                    graph.getModel().endUpdate();
                }

                graph.getSelectionModel().addListener(mxEvent.CHANGE, function(sender, evt) {
                    selectionChanged(graph);
                });

                selectionChanged(graph);

                if (mxClient.IS_QUIRKS) //IS_QUIRKS:True if the current browser is Internet Explorer and it is in quirks mode.
                {
                    document.body.style.overflow = 'hidden';
                    new mxDivResizer(container);
                }

                window.editing = false;

            }
        };
    </script>
</head>

<!-- Page passes the container for the graph to the program -->

<body onload="main(document.getElementById('graphContainer'));" style="margin:4px;">

    <table style="margin-left:auto; margin-right:auto; width:1600px; text-align:center;">
        <tr>
            <td>
                <div id="tools" style="border: solid 3px black; padding: 10px;  height: 50px;">
                    <button class="tool_button" onclick="window.location.href = 'midpage.html';">離開</button>
                    <button class="tool_button" onclick="editMode()" id="editBtn">編輯模式</button>
                    <button class="tool_button" onclick="viewMode()" id="viewBtn" disabled="true">檢視模式</button>
                </div>
            </td>
            <td style="border: solid 3px black; padding: 10px;  height: 50px;">
                <div id="msgBox">
                    <font size="5" id="edit_message">viewMode</font>
                </div>
            </td>
        </tr>

        <tr>
            <td rowspan="2">
                <div id="graphContainer" style="border: solid 3px black; overflow:hidden; padding: 10px; width:1200px; height:900px;">
                </div>
            </td>
            <td>
                <div id="properties" style="border: solid 3px black; padding: 10px; width:400px;height:570px;font-size:30px">
                </div>
            </td>
        </tr>
        <tr>
            <td>
                <div id="properties2" style="border: solid 3px black; padding: 10px; width:400px;height:300px; text-align:left;">
                    <h2 id="seatingid">座位表id: </h2>
                    <h2 id="examid">考試id: </h2>
                    <h2 id="states">考試狀態: </h2>
                    <div class="state_colors state_unassigned"></div>
                    <h3 id="state_unassigned">未登入</h3>
                    <div class="state_colors state_login"></div>
                    <h3 id="state_login">正常登入</h3>
                    <div class="state_colors state_backupLogin"></div>
                    <h3 id="state_backupLogin">備用登入</h3>
                </div>
            </td>
        </tr>
    </table>
</body>
<style>
    .tool_button {
        padding: 15px 20px;
        margin: 1px 10px
    }
    
    .state_colors {
        float: left;
        width: 15px;
        height: 15px;
        margin: 5px;
        border: 1px solid rgba(0, 0, 0, .2);
    }
    
    .state_unassigned {
        background: #CFD2DE;
    }
    
    .state_login {
        background: #D1E1CB;
    }
    
    .state_backupLogin {
        background: #F5BE8E;
    }
</style>

</html>