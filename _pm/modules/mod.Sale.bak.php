<?php

	class Sale extends AbstractModule
	{
		var $itemsCount = 0;
		
		var $LOW_DETAILED = 0;
		var $MEDIUM_DETAILED = 1;
		var $HI_DETAILED = 2;
		
		function Sale()
		{
			$this->publicFunctions = array("getContent", "getBlock", "getTopBlock", "getSubItemType", "getItemType", "getItemDesc", 
            "getSpecificDataForEditing", "getSpecificBlockDesc", "updateSpecificData");
	
		}

		function getContent($args)
		{
			global $structureMgr, $templatesMgr;
			
			SetCfg("Sale.itemsPerPage", 10);
			SetCfg("Sale.itemsPerCol", 1);
			
			$order = _get("order");

			$pageID = $args[0];

			$content = "";
			$pager = "";
			
			$topContent = $structureMgr->getData($pageID);

			

			$pNum = $structureMgr->getPageNumberByPageID($pageID);
            $URL = $structureMgr->getPathByPageID($pageID, false);
			
			$perPage = GetCfg("Sale.itemsPerPage");

            $startFrom = ($pNum - 1) * $perPage;
            $endAt = $startFrom + $perPage - 1;
            
			$items = $this->getItems($startFrom, $endAt, $order);

            $cnt = $this->itemsCount;

            if ($endAt >= $cnt)
                $endAt = $cnt - 1;

            $pagesCount = ceil($cnt / $perPage);

            if ($pagesCount < $pNum)
            {
                trigger_error("Invalid pageNumber [$pNum of $pagesCount] - possibly hacking or serious change in DB", PM_ERROR);
            }
            else
            {
                if ($pagesCount > 1)
                {
                    $tpl = $templatesMgr->getTemplate(-1, GetCfg("TemplatesPath") . "/" . "pager_filter.html");
                    $purePager = "";

                    for ($i=1; $i <= $pagesCount; $i++)
                    {
                        if ($i > 1)
                        {
                            $purePager .= " - ";
                            $u = $URL . "/page" . $i;
                        }
                        else
                           $u = $URL;

                        if ($filter)
                            $u .= "?" . $filter;

                        if ($i == $pNum)
                        {
                            $purePager .= $i;
                        }
                        else
                        {
                            $purePager .= "<a href=\"$u\" class=\"levm\">" . $i . "</a>";
                        }
                    }
					switch($order) {
						case "name" :$tpl = str_replace("%sel1%", 'selected="selected"', $tpl); break;
						case "price" :$tpl = str_replace("%sel2%", 'selected="selected"', $tpl); break;
						case "desc" :$tpl = str_replace("%sel3%", 'selected="selected"', $tpl); break;
					}
					$tpl = str_replace("%sel1%", '', $tpl);
					$tpl = str_replace("%sel2%", '', $tpl);
					$tpl = str_replace("%sel3%", '', $tpl);
					$tpl = str_replace("%links%", $purePager, $tpl);

					$tpl =str_replace("%catFilter%", "", $tpl);
                    $pager = str_replace("%links%", $purePager, $tpl);
                }

			}
			
			$i = 1;
			$content .= "<div class=\"items\">\n<table cellpadding=\"0\" cellspacing=\"0\" class=\"items-table\">\n";
			foreach($items as $item) {
				if(($i-1) % GetCfg("Sale.itemsPerCol") == 0) {
					$content .= "<tr>\n";
				}
				$style = "td".(($i > GetCfg("Sale.itemsPerCol")) ? "dwn" : "up")."left";
				$content .= $this->getFilledTemplate($item, $this->HI_DETAILED, $style);
				if($i % GetCfg("Sale.itemsPerCol") == 0) {
					$content .= "\n</tr>\n";
				}
				$i++;
			}
			$content .= "\n</table>\n</div>\n";
			
			return $topContent.$pager.$content.$pager;
		}

		function getBlock()
		{

			SetCfg("Sale.itemsPerPage", 4);
			SetCfg("Sale.itemsPerCol", 2);

			$content = "";
			

			$items = $this->getRandomBlockItems();
			$i = 1;
			$content .= "<div class=\"items\">\n<table cellpadding=\"0\" cellspacing=\"0\" class=\"items-table\">\n";
			foreach($items as $item) {
				if(($i-1) % GetCfg("Sale.itemsPerCol") == 0) {
					$content .= "<tr>\n ";
				}
				$style = "td".(($i > GetCfg("Sale.itemsPerCol")) ? "dwn" : "up").(($i % GetCfg("Sale.itemsPerCol") != 0) ? "left" : "right");
				$content .= $this->getFilledTemplate($item, $this->MEDIUM_DETAILED, $style);
				if($i % GetCfg("Sale.itemsPerCol") == 0) {
					$content .= "\n</tr>\n";
				}
				$i++;
			}
			$content .= "\n</table>\n";
			
			return $content.'<div class="more"><img src="/images/arr_gray2.gif" width="7" height="9"  alt="" /><a href="/catalogue/sale">��������� � �����������</a></div></div>';
		}

		function getTopBlock()
		{

			SetCfg("Sale.itemsPerPage", 4);
			SetCfg("Sale.itemsPerCol", 1);

			$content = "";
			

			$items = $this->getRandomBlockItems();
			foreach($items as $item) {
				$content .= $this->getFilledSmallTemplate($item, $this->LOW_DETAILED);
			}
			
			return $content.'<div class="more"><img src="/images/arr_gray2.gif" width="7" height="9"  alt="" /><a href="/catalogue/sale/">��������� � �����������</a></div>';
		}

		function getFilledSmallTemplate($catItem, $detailed = 0)
		{
			global $structureMgr, $templatesMgr;
            
            if (count($catItem) == 0)
                trigger_error("Invaid function call - arguments array is empty.", PM_FATAL);

            $URL = $structureMgr->getPathByPageID($catItem["sID"], false);
            $tpl = $templatesMgr->getTemplate(-1, GetCfg("TemplatesPath") . "/Catalogue/sale.html");
            $tpl = str_replace("%title%", $catItem["ShortTitle"], $tpl);
            $tpl = str_replace("%link%", $URL, $tpl);
            
            //price generation must be moved to special function as it is called from at least two places
            if ($catItem["ptPercent"] == 0)
                $firstPrice = "<span class=\"t_salepr\">" . round($catItem["salePrice"] - ($catItem["salePrice"] * 5 / 100)) . "</span>";
            else
                $firstPrice = "<strong><span class=\"t_salepr\">" . 
                              round($catItem["salePrice"] - ($catItem["salePrice"] * $catItem["ptPercent"] / 100)) . 
                              "</span></strong>";


            

            $tpl = str_replace("%price%", "<span class=\"t_salepr\">".$firstPrice ."</span> / <span class=\"t_old\">".$catItem["salePrice"]."</span> ���.", $tpl);
			
			$imgPercent = "";
			switch($catItem["ptID"]) {
				case "5": $imgPercent = "<img src=\"/images/sale/sale_b_01.gif\" width=\"70\" height=\"70\"  alt=\"\" />"; break;
				case "6": $imgPercent = "<img src=\"/images/sale/sale_b_02.gif\" width=\"70\" height=\"70\"  alt=\"\" />"; break;
				case "7": $imgPercent = "<img src=\"/images/sale/sale_b_03.gif\" width=\"70\" height=\"70\"  alt=\"\" />"; break;
			}

			$tpl = str_replace("%bonus%", $imgPercent, $tpl);

			if($detailed > 0) {
				$tpl = str_replace("%producer%", "<strong>�������������: </strong>" . $catItem["accPlantName"], $tpl);
			} else {
				$tpl = str_replace("%producer%", "", $tpl);
			}
			$props = $this->getCatItemProperties($catItem["sID"], "CatItem", $structureMgr->getParentPageID($catItem["sID"]));
			if($detailed > 1)
			{	
				$prop_list = "";
				foreach($props as $prop)
				{
				   if ($prop[3] && !$prop[4])
				   {
					   $prop_list .= "<strong>$prop[1]:</strong> $prop[3] $prop[2]<br/>\n";
				   }
					 
				};
				$tpl = str_replace("%props%", $prop_list, $tpl);
			
				
				if (!isset($catItem["Compatibility"])) {
					$catItem["Compatibility"] = "";
				} else
				{
					$catItem["Compatibility"] = "<strong>�����:</strong>" . $catItem["Compatibility"];
				}

				$tpl = str_replace("%car_compatibility%", $catItem["Compatibility"], $tpl);
			} else if( $detailed == 1){
				$prop_list = "";
				//foreach($props as $prop)
				//{
				   //if ($prop[3] && !$prop[4])
				   //{
					   $prop_list = "<strong>".$props[0][1].":</strong> ".$props[0][3]." ".$props[0][2]."<br/>\n";
				   //}
					 
				//};
				$tpl = str_replace("%props%", $prop_list, $tpl);
				$tpl = str_replace("%car_compatibility%", "", $tpl);
			} else {
				$tpl = str_replace("%props%", "", $tpl);
				$tpl = str_replace("%car_compatibility%", "", $tpl);
			}
            if ($catItem["smallPicture"] == NULL)
            {
                if (file_exists(GetCfg("ROOT") . $catItem["PicturePath"] . "/" . $catItem["sID"] . ".gif"))
                    $catItem["smallPicture"] = $catItem["PicturePath"] . "/" . $catItem["sID"] . ".gif";
                else
                if ($catItem["logotype"] == NULL)
                    $catItem["smallPicture"] = "/products/empty.gif";
                else
                    $catItem["smallPicture"] = $catItem["logotype"];
            }
            $tpl = str_replace("%picture%", "<img width=\"70\" height=\"70\" src=\"" . $catItem["smallPicture"] . "\" alt=\"" . $catItem["ShortTitle"] . "\"/>", $tpl);
            $tpl = str_replace("%goodID%", $catItem["accID"], $tpl);



            return $tpl;
		}


		function getFilledTemplate($catItem, $detailed = 0, $columnStyle = "tdupleft")
		{
			global $structureMgr, $templatesMgr;
            
            if (count($catItem) == 0)
                trigger_error("Invaid function call - arguments array is empty.", PM_FATAL);
			
			$catItem["tplID"] = $catItem["Compatibility"] ? 1 : 2;

            $URL = $structureMgr->getPathByPageID($catItem["sID"], false);
            $tpl = $templatesMgr->getTemplate(-1, GetCfg("TemplatesPath") . "/Catalogue/bonus".$catItem["tplID"] . ".html");
            $tpl = str_replace("%title%", $catItem["ShortTitle"], $tpl);
            $tpl = str_replace("%link%", $URL, $tpl);
			
			$tpl = str_replace("%columnStyle%", $columnStyle, $tpl);

			$tpl = str_replace("%typename%", "����������", $tpl);
            $tpl = str_replace("%type%", "t_sale", $tpl);
            
            //price generation must be moved to special function as it is called from at least two places
            if ($catItem["ptPercent"] == 0)
                $firstPrice = "<span class=\"t_salepr\">" . round($catItem["salePrice"] - ($catItem["salePrice"] * 5 / 100)) . "</span>";
            else
                $firstPrice = "<span class=\"t_salepr\">" . 
                              round($catItem["salePrice"] - ($catItem["salePrice"] * $catItem["ptPercent"] / 100)) . 
                              "</span>";


            

            $tpl = str_replace("%price%", "<span class=\"t_bonus\">$firstPrice" . "</span> / <span class=\"t_old\">" . $catItem["salePrice"] . "</span> ���.", $tpl);

			$tpl = str_replace("%bonus%", $catItem["ptPercent"], $tpl);

			if($detailed > 0) {
				$tpl = str_replace("%producer%", "<strong>�������������: </strong>" . $catItem["accPlantName"], $tpl);
			} else {
				$tpl = str_replace("%producer%", "", $tpl);
			}
			$props = $this->getCatItemProperties($catItem["sID"], "CatItem", $structureMgr->getParentPageID($catItem["sID"]));
			if($detailed > 1)
			{	
				$prop_list = "";
				foreach($props as $prop)
				{
				   if ($prop[3] && !$prop[4])
				   {
					   $prop_list .= "<strong>$prop[1]:</strong> $prop[3] $prop[2]<br/>\n";
				   }
					 
				};
				$tpl = str_replace("%props%", $prop_list, $tpl);
				if (!isset($catItem["Compatibility"])) {
					$catItem["Compatibility"] = "";
				} else
				{
					$catItem["Compatibility"] = "<strong>�����:</strong>" . $catItem["Compatibility"];
				}

				$tpl = str_replace("%car_compatibility%", $catItem["Compatibility"], $tpl);
			} else if( $detailed == 1){
				$prop_list = "";
				//foreach($props as $prop)
				//{
				   if ($props[0][3] && !$props[0][4])
				   {
					   $prop_list = "<strong>".$props[0][1].":</strong> ".$props[0][3]." ".$props[0][2]."<br/>\n";
				   }
					 
				//};
				$tpl = str_replace("%props%", $prop_list, $tpl);
				if (!isset($catItem["Compatibility"])) {
					$catItem["Compatibility"] = "";
				} else
				{
					$catItem["Compatibility"] = "<strong>�����:</strong>" . $catItem["Compatibility"];
				}

				$tpl = str_replace("%car_compatibility%", $catItem["Compatibility"], $tpl);
			} else {
				$tpl = str_replace("%props%", "", $tpl);
				$tpl = str_replace("%car_compatibility%", "", $tpl);
			}
            if ($catItem["smallPicture"] == NULL)
            {
                if (file_exists(GetCfg("ROOT") . $catItem["PicturePath"] . "/" . $catItem["sID"] . ".gif"))
                    $catItem["smallPicture"] = $catItem["PicturePath"] . "/" . $catItem["sID"] . ".gif";
                else
                if ($catItem["logotype"] == NULL)
                    $catItem["smallPicture"] = "/products/empty.gif";
                else
                    $catItem["smallPicture"] = $catItem["logotype"];
            }
            $tpl = str_replace("%picture%", "<img width=\"70\"  height=\"70\" src=\"" . $catItem["smallPicture"] . "\" alt=\"" . $catItem["ShortTitle"] . "\"/>", $tpl);
            $tpl = str_replace("%goodID%", $catItem["accID"], $tpl);



            return $tpl;
		}
		
		function getCatItemProperties($pageID, $DataType, $parentID)
        {
            global $structureMgr;
            
            if ($pageID != -1)
                $md = $structureMgr->getMetaData($pageID);
            else
                $md["DataType"] = $DataType;

            $res = array();

            switch ($md["DataType"])
            {
                case "CatItem":
                {
                    if ($pageID != -1)
                    {
                        $q2 = "SELECT accID FROM pm_as_parts WHERE sID = $pageID";
                        list($accID) = mysql_fetch_row(mysql_query($q2));
                        if (!$accID)
                            trigger_error("Error fetching accID for CatItemProperties (sID=$pageID) [$q2]" . mysql_error(), PM_FATAL);


                        $q = "SELECT app.propListID, propName, accMeasure, propValue, isHidden FROM pm_as_prop_list apl, pm_as_parts_properties app
                        WHERE app.accID=$accID AND app.propListID = apl.propListID
                        ORDER BY apl.OrderNumber";

                        $qr = mysql_query($q);
                        if (!$qr)
                            trigger_error("Error while query [$q] - " . mysql_error(), PM_FATAL);
                        
                        while (false !== ($row = mysql_fetch_row($qr)))
                        {
                            $res[] = $row;
                        }
                    }
                    else
                    {
                        $branch = $structureMgr->getCurrentBranch($parentID);
                        for ($i = count($branch) - 1; $i >=0; $i--)
                        {
                            $accCatID = $this->getCatIDByPageID($branch[$i]);
                            if ($accCatID == -1)
                                break;

                            $q2 = "SELECT propListID, propName, accMeasure, '', isHidden FROM pm_as_prop_list WHERE accCatID=$accCatID";
                            $qr = mysql_query($q2);
                            if (!$qr)
                                trigger_error("Error fetching propNames for CatItems - " . mysql_error(), PM_FATAL);
                            if (mysql_num_rows($qr) > 0)
                            {
                                while (false !== ($prop = mysql_fetch_row($qr)))
                                {
                                    $res[] = $prop;
                                }
                                break;
                            }
                        }
                    }
                    return $res;
                }
                default:
                    return array();
            }
        }

		function getRandomBlockItems()
		{
			$query = "SELECT DISTINCT
					accID, p.sID, ShortTitle, deliveryCode, accPlantName, logotype, smallPicture, p.tplID, salePrice, 
					MustUseCompatibility, PicturePath, DescriptionTemplate, ptPercent, pt.ptID
					FROM `pm_as_parts` p
					LEFT JOIN pm_as_producer ap ON (ap.accPlantID = p.accPlantID)
					LEFT JOIN pm_structure s ON (p.sID = s.sID)
					LEFT JOIN pm_as_categories ac ON (s.pms_sID = ac.sID)
					LEFT JOIN pm_as_pricetypes pt ON (pt.ptID = p.ptID)
					WHERE pt.ptID = 5 || pt.ptID = 6 || pt.ptID = 7
					ORDER BY RAND()
					LIMIT ".GetCfg("Sale.itemsPerPage")."
				";
			$result = mysql_query($query);
			if (!$result)
                trigger_error("Invaid query. " . mysql_error(), PM_FATAL);

            if (mysql_num_rows($result) == 0)
                trigger_error("Empty result for $query", PM_WARNING);
			
			$catItems = array();

			while($item = mysql_fetch_assoc($result)) {
				if ($item["MustUseCompatibility"])
				{
					$item["Compatibility"] = "";
					$query2 = "SELECT atc.carID, carModel, carName FROM pm_as_acc_to_cars atc LEFT JOIN pm_as_cars c ON (c.carID = atc.carID) 
					WHERE accID=" . $item["accID"];
					$result2 = mysql_query($query2);

					if (!$result2)
						trigger_error("Error retrieving car model links [$query2] - " . mysql_error(), PM_FATAL);
					
					while (false !== (list($carID, $carModel, $carName) = mysql_fetch_row($result2)))
					{
						if ($item["Compatibility"])
							$item["Compatibility"] .= ", ";

						$item["Compatibility"] .= "$carModel";
						if ($carName)
							$item["Compatibility"] .= " $carName";
					}
				}
				$catItems[] = $item;
			}

			return $catItems;
		}

		function getItems($startFrom, $endAt, $order)
		{
			$orderStr = " ORDER BY ";
			if ($order == 'name') {
				$orderStr .= "ShortTitle";
			} elseif ($order == 'price') {
				$orderStr .= "salePrice";
			} elseif ($order == 'pricedesc') {
				$orderStr .= "salePrice desc";
			} else {
				$orderStr .= "ShortTitle";
			}

			$query = "SELECT SQL_CALC_FOUND_ROWS
					accID, p.sID, ShortTitle, deliveryCode, accPlantName, logotype, smallPicture, p.tplID, salePrice, 
					MustUseCompatibility, PicturePath, DescriptionTemplate, ptPercent  
					FROM `pm_as_parts` p
					LEFT JOIN pm_as_producer ap ON (ap.accPlantID = p.accPlantID)
					LEFT JOIN pm_structure s ON (p.sID = s.sID)
					LEFT JOIN pm_as_categories ac ON (s.pms_sID = ac.sID)
					LEFT JOIN pm_as_pricetypes pt ON (pt.ptID = p.ptID)
					WHERE pt.ptID = 5 || pt.ptID = 6 || pt.ptID = 7
					".$orderStr."
					LIMIT ".$startFrom.",".GetCfg("Sale.itemsPerPage")."
				";
			$result = mysql_query($query);
			if (!$result)
                trigger_error("Invaid query. " . mysql_error(), PM_FATAL);

            if (mysql_num_rows($result) == 0)
                trigger_error("Empty result for $query", PM_WARNING);

			$query = "SELECT FOUND_ROWS() as itemsCount";
			$res = mysql_query($query);
			$row = mysql_fetch_assoc($res);
			$this->itemsCount = $row['itemsCount'];


			$catItems = array();

			while($item = mysql_fetch_assoc($result)) {
				if ($item["MustUseCompatibility"])
				{
					$item["Compatibility"] = "";
					$query2 = "SELECT atc.carID, carModel, carName FROM pm_as_acc_to_cars atc LEFT JOIN pm_as_cars c ON (c.carID = atc.carID) 
					WHERE accID=" . $item["accID"];
					$result2 = mysql_query($query2);

					if (!$result2)
						trigger_error("Error retrieving car model links [$query2] - " . mysql_error(), PM_FATAL);
					
					while (false !== (list($carID, $carModel, $carName) = mysql_fetch_row($result2)))
					{
						if ($item["Compatibility"])
							$item["Compatibility"] .= ", ";

						$item["Compatibility"] .= "$carModel";
						if ($carName)
							$item["Compatibility"] .= " $carName";
					}
				}
				$catItems[] = $item;
			}

			return $catItems;
		}

		function getSpecificDataForEditing($args)
        {
            return array();
        }

        function updateSpecificData($args)
        {
            return true;
        }

        function getSpecificBlockDesc($args)
        {
            $DataType = $args[0];
            switch ($DataType)
            {
                case "Sale":
                    return "���������";
            }
            
            return "";
        }

        function getItemDesc($args)
        {
            $DataType = $args[0];
            switch ($DataType)
            {
                case "Sale":
                    return "";
            }
            
            return "";
        }

        function getItemType($args)
        {
            $DataType = $args[0];
            switch ($DataType)
            {
                case "Sale":
                    return array("����������", "����������", "����������"); //������, ���, ���
            }
            
            return array();
        }

        function getSubItemType($args)
        {
            $DataType = $args[0];

            switch ($DataType)
            {
                case "Catalogue":
                    return array("Sale" => "����������");
				case "Article":
                    return array("Sale" => "����������");
            }
            return array();
        }

        
	}

?>