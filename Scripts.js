
function GoToDir(newfolder)
{
    document.getElementById("folder").setAttribute("value", newfolder);
    document.forms["gotofolder"].submit();
}

function GoUpperDir()
{
    document.forms["goup"].submit();
}

function CheckAllFiles()
{
    var checkBoxes = document.getElementsByName('filename[]');
    if(document.getElementById("selectall").checked)
    {
        for(var i = 0; checkBoxes.length > i; i++)
        {
            checkBoxes[i].checked = true;
        }
    }
    else
    {
        for(var i = 0; checkBoxes.length > i; i++)
        {
            checkBoxes[i].checked = false;
        }
    }
}

function CheckIfAllIsSelected()
{
    var checkBoxes = document.getElementsByName("filename[]");
    var counter = 0;
    for(var i = 0; checkBoxes.lenght > i; i++)
    {
        if(checkBoxes[i].checked == true)
        {
            counter++;
        }
    }
    if(counter == checkBoxes.length)
    {
        document.getElementById("selectall").checked = true;
    }
    else
    {
        document.getElementById("selectall").checked = false;
    }
}

function SubmitSelections(selectedFunction)
{
    document.getElementById("actionType").setAttribute("value", selectedFunction);
    document.forms["filelist"].submit();
}

function UploadFile()
{
    document.forms["fileupload"].submit();
}