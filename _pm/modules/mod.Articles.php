<?
    class Articles extends AbstractModule
    {
        function Articles()
        {
            $this->publicFunctions = array("getContent", "getSubItemType", "getItemType", "getItemDesc", 
            "getSpecificDataForEditing", "getSpecificBlockDesc", "updateSpecificData", "updateAdditionalColumns");
            $this->cmdFunctions = array();
        }

        function updateSpecificData($args)
        {
            global $structureMgr;
            $sData = array();
            $qSet = "";
            

            if ($args[0] != -1)
            {
                $md = $structureMgr->getMetaData($args[0]);
            }
            else
            {
                trigger_error("pageID must be specified", PM_WARNING);
                return false;
            }

            return true;
        }
        
        function updateAdditionalColumns($args)
        {
            return true;
        }
        
        function getSpecificDataForEditing($args)
        {
            return array();
        }

        function getSpecificBlockDesc($args)
        {
            return "";
        }

        function getItemDesc($args)
        {
            $DataType = $args[0];
            switch ($DataType)
            {
                case "Article":
                    return "����� ������";
            }
            
            return "";
        }

        function getItemType($args)
        {
            $DataType = $args[0];
            switch ($DataType)
            {
                case "Article":
                    return array("������", "������", "������");
            }
            
            return array();
        }

        function getSubItemType($args)
        {
            $DataType = $args[0];

            switch ($DataType)
            {
                case "Article":
                    return array("Article" => "��������� (������)");
            }
            
            return array();
        }
        
        function getContent($args)
        {
            global $structureMgr;
            return "<div class=\"podbor\">".$structureMgr->getData($args[0])."</div>";
        }
    }
?>
