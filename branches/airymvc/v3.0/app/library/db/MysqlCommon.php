<?php
/**
 * AiryMVC Framework
 *
 * @category AiryMVC
 * @license New BSD license - at this URL: http://opensource.org/licenses/BSD-3-Clause
 * @author: Hung-Fu Aaron Chang
 */
/**
 * This abstract class is used for composing each kind of the SQL statement.
 *
 * @filesource
 * @package framework\app\library\db\MysqlCommon
 * @license New BSD license - at this URL: http://opensource.org/licenses/BSD-3-Clause
 */
abstract class MysqlCommon extends SqlComponent{

    function __construct() {
		$this->setOpenIdentifier("`");
		$this->setCloseIdentifier("`");
    }
    
    /**
     * @deprecated Deprecated method 
     * @param array $tables
     * @return MysqlCommon
     */
    public function innerJoin($tables) {
        //INNER JOIN messages INNER JOIN languages

        foreach ($tables as $index => $tbl) {
        	$addon = "";
            if ($index != 0) {
            	$addon = $this->joinPart;
            }
            $this->joinPart = "{$addon} INNER JOIN {$this->openIdentifier}{$tbl}{$this->closeIdentifier}";
        }
        return $this;
    }
    

    /**
     * @example
     * 
     * Deprecated method 
     * 
     * conditions represent 
     * Ex: array ("" => array(array("=", table1=>field1, table2=>field2)))
     *     array ("AND" => array(array("=", table1=>field1, table2=>field2), array("<>", table3=>field3, table2=>field2)
     *                   , array("<>", table4=>field4, table3=>field3)), 
     *              "OR"=> array(array("=", table5=>field5, table6=>field6)))
     * operators represent "AND",  "OR" its squence matters.
     * if operators is null, all operators are "AND"
     * 
     * SELECT * FROM `event` INNER JOIN `event_report` INNER JOIN `member` 
     * ON `table1`.`field1` = `table2`.`field2`AND `table3`.`field3` <> `table2`.`field2`AND `table4`.`field4` <> `table3`.`field3`
     * OR `table5`.`field5` = `table6`.`field6` LIMIT 0, 10
     * 
     * @param array $condition
     * @return SqlComponent
     * 
     */
    public function joinOn($condition) {
        $this->joinOnPart = " ON ";
        if (is_array($condition)) {
        	$this->joinOnPart = $this->composeJoinOnByArray($this->joinOnPart, $condition);
        } else {
        	$this->joinOnPart = $this->composeJoinOnByString($this->joinOnPart, $condition);
        }
        return $this;
    }
    
    /**
     * @param array $condition
     * @return MysqlCommon
     */
    public function andJoinOn($condition) {
        $this->joinOnPart .= " AND {$condition}";
        return $this;
    }
    
    /**
     * @param array $condition
     * @return MysqlCommon
     */
    public function orJoinOn($condition) {
        $this->joinOnPart .= " OR {$condition}";
        return $this;
    }
    
    /**
     * @param string $joinOnString
     * @param string $conditionString
     * @return string
     */
    protected function composeJoinOnByString($joinOnString, $conditionString) {
    	$joinOnString .= $conditionString;
    	return $joinOnString;
    }
    
    /**
     * @param string $joinOnString
     * @param array $condition
     * @return string
     */
    protected function composeJoinOnByArray($joinOnString, $condition) {
        $ops = array_keys($condition);
        
        if (empty($ops[0])) {
            //NO "AND", "OR" 
            $joinOnString = $this->attachJoinOn($joinOnString, $condition[$ops[0]][0]);
        } else {   
        	//Multiple Join Conditions
            if ((count($ops) == 1))  {
                $op = $ops[0];
                $tfPairs = $condition[$op];
                if (count($tfPairs) == 1) {
                    $tfPair = $tfPairs[0];
                    $joinOnString = $this->attachJoinOn($joinOnString, $tfPair);      
                    return $joinOnString;
                }
				$joinOnString = $this->attachPairs($joinOnString, $tfPairs, $op);
                return $joinOnString;
            }
            foreach ($ops as $index => $op) {
                $tfPairs = $condition[$op]; 
                if (count($tfPairs) == 1 && $index > 0) { 
                    $tfPair = $tfPairs[0]; 
                    $joinOnString = $this->attachJoinOn($joinOnString, $tfPair, null, $op);   
                } elseif (count($tfPairs) > 1) {
					$joinOnString = $this->attachPairs($joinOnString, $tfPairs, $op);
                }              
            }
            
        }  
        return $joinOnString;  	
    }
    
    /**
     * 
     * @param string $joinOnString
     * @param array $tfPairs
     * @param string $op
     * @return string
     */
    protected function attachPairs($joinOnString, $tfPairs, $op) {
        foreach ($tfPairs as $idx => $tfPair) {
                 $operation = $op;
                 if (count($tfPairs) - 1 == $idx) {
                     $operation = null;  
                 }
                 $joinOnString = $this->attachJoinOn($joinOnString, $tfPair, $operation);
        }
        return $joinOnString;    	
    }
    
    /**
     * 
     * @param string $joinOnString
     * @param array $tf_pair
     * @param string $op
     * @param string $leadingOp
     * @return string
     */
    protected function attachJoinOn($joinOnString, $tf_pair, $op = null, $leadingOp = null) {
        $op = is_null($op) ? "" : $op;
        $leadingOp = is_null($leadingOp) ? "" : (" " . $leadingOp);	
        $mkeys = array_keys($tf_pair);
        $mopr = $tf_pair[0];
        $mtable1 = $mkeys[1];
        $mtable2 = $mkeys[2];
        $joinOnString .= $leadingOp . " {$this->openIdentifier}" . $mtable1 . "{$this->closeIdentifier}.{$this->openIdentifier}" . $tf_pair[$mtable1] . "{$this->closeIdentifier} " . $mopr . " {$this->openIdentifier}" . $mtable2 . "{$this->closeIdentifier}.{$this->openIdentifier}" . $tf_pair[$mtable2] . "{$this->closeIdentifier} ". $op;
        
        return $joinOnString;    	
    }
    
    public function execute() {}
    
}

?>
