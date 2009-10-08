window.onload = function()
{
    document.getElementById("clear").onclick = function()
    {
        document.getElementById("input").value = "";
        document.getElementById("editName").value = "";
    }
}

function edit(id)
{
    document.getElementById("input").value = document.getElementById("name" + id).innerHTML;
    document.getElementById("editName").value = id;
}

function del()
{
    if (confirm("��� ������ � ��������� ���������� ����� �������.\n���������� ��������?")) {
        document.getElementById("action").value = "d";
        document.forms[1].submit();
    }
}

function hide()
{
    document.getElementById("action").value = "h";
    document.forms[1].submit();
}

function pub()
{
    document.getElementById("action").value = "p";
    document.forms[1].submit();
}