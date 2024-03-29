<?php
function alertBox($message, $ref = '#', $btnTxt = 'Close')
{
    return  <<<HTML
            <div class="modal fade" id="myModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
            <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">ALERT</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
                </div>
                <div class="modal-body">
                {$message}
                </div>
                <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal" onclick="location.href='{$ref}'">
                {$btnTxt}
                </button>
                </div>
            </div>
            </div>
            </div>
            <script type="text/javascript">
                $(document).ready(function(){
                    $("#myModal").modal("show");
                });
            </script>
            HTML;
            
}

function success($message=''){
    return <<<HTML
            <script>
            $(function () {
                $.notify({
                    title: 'SUCCESS',
                    message: '{$message}'
                },
                {   
                    type: 'success',
                    newest_on_top: true
                }
                )
            });
            </script>
            HTML;
}

function error($message=''){
    return <<<HTML
            <script>
            $(function () {
                $.notify({
                    title: 'ERROR',
                    message: '{$message}'
                },
                {   
                    type: 'danger',
                    newest_on_top: true
                }
                )
            });
            </script>
            HTML;
}

function editButton($data)
{
    $data = json_encode($data); //Must use single brace for data-service container!
    return      <<<HTML
                <button type="button" class="btn btn-outline-primary" data-toggle="modal" data-target="#Modal" data-type="edit" data-service='{$data}'> 
                <svg width="1em" height="1em" viewBox="0 0 16 16" class="bi bi-pencil" fill="currentColor" xmlns="http://www.w3.org/2000/svg">
                    <path fill-rule="evenodd" d="M12.146.146a.5.5 0 0 1 .708 0l3 3a.5.5 0 0 1 0 .708l-10 10a.5.5 0 0 1-.168.11l-5 2a.5.5 0 0 1-.65-.65l2-5a.5.5 0 0 1 .11-.168l10-10zM11.207 2.5L13.5 4.793 14.793 3.5 12.5 1.207 11.207 2.5zm1.586 3L10.5 3.207 4 9.707V10h.5a.5.5 0 0 1 .5.5v.5h.5a.5.5 0 0 1 .5.5v.5h.293l6.5-6.5zm-9.761 5.175l-.106.106-1.528 3.821 3.821-1.528.106-.106A.5.5 0 0 1 5 12.5V12h-.5a.5.5 0 0 1-.5-.5V11h-.5a.5.5 0 0 1-.468-.325z"></path>
                </svg>
                </button>
                HTML;
}

function deleteButton($id, $action='')
{
    return      <<<HTML
                <form action="{$action}" method="post" role="form" style="display:inline;">
                    <input name="id" value="{$id}" hidden>
                    <button name="delete" type="submit" class="btn btn-outline-danger">
                        <svg width="1em" height="1em" viewBox="0 0 16 16" class="bi bi-trash" fill="currentColor" xmlns="http://www.w3.org/2000/svg">
                            <path d="M5.5 5.5A.5.5 0 0 1 6 6v6a.5.5 0 0 1-1 0V6a.5.5 0 0 1 .5-.5zm2.5 0a.5.5 0 0 1 .5.5v6a.5.5 0 0 1-1 0V6a.5.5 0 0 1 .5-.5zm3 .5a.5.5 0 0 0-1 0v6a.5.5 0 0 0 1 0V6z"></path>
                            <path fill-rule="evenodd" d="M14.5 3a1 1 0 0 1-1 1H13v9a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2V4h-.5a1 1 0 0 1-1-1V2a1 1 0 0 1 1-1H6a1 1 0 0 1 1-1h2a1 1 0 0 1 1 1h3.5a1 1 0 0 1 1 1v1zM4.118 4L4 4.059V13a1 1 0 0 0 1 1h6a1 1 0 0 0 1-1V4.059L11.882 4H4.118zM2.5 3V2h11v1h-11z"></path>
                        </svg>
                    </button>
                </form>
                HTML;
}
?>
