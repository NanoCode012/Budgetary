<?php
function alertBox($message, $ref = '#')
{
    return '<div class="modal fade" id="myModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
            <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">ALERT</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
                </div>
                <div class="modal-body">
                ' . $message .
                '</div>
                <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal" onclick="location.href=\' ' . $ref . ' \'">Close</button>
                </div>
            </div>
            </div>
            </div>
            <script type="text/javascript">
                $(document).ready(function(){
                    $("#myModal").modal("show");
                });
            </script>';
}
?>