<?

    SetCfg("Catalogue.ModuleName", "�������");
    SetCfg("Catalogue.ModuleDesc", "");

    SetCfg("Catalogue.dictionaries", 
            array(

                "pm_as_producer" => array("�������������", 
                                          array(            //colName, colWidth for edit, not read-only
                                              "id" => array("accPlantID", "4", 0), 
                                              "�������������" => array("accPlantName", "32", 1), 
                                              "�������" => array("logotype", "18", 1),
                                              "������� �������" => array("logotypeb", "18", 1),
                                              "�� ��������" => array("sID", "5", 1)), 
                                          "accPlantID" //order by
                                         ),

                "pm_as_pricetypes" => array("���� ���", 
                                          array(
                                              "id" => array("ptID", "4", 0), 
                                              "������������ ����" => array("ptName", "32", 1), 
                                              "������� ������" => array("ptPercent", "4", 1)),
                                          "ptID"
                                         )
            )                            
    );

?>