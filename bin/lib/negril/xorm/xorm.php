<?php
namespace Negril\xOrm;
interface IEntity{function IsEvaluated();}

abstract class EntityBase implements IEntity{
    public function IsEvaluated(){return true;}
    
    public static function createQuery(){
        return new EntityActionController(get_class(static));
    }
}

abstract class EntityActionController{
    public function isOnTheFly(){
        
    }
    public function save(){
        
    }
    public function delete(){
        
    }
}

class DbContext{
    
}

final class XORM{
	public static$Instance;
    public
        $Settings                   =true
    ;
	
    public function __toString(){return'xORM by Ali Mousavi Kherad for [AWESOME] php!';}
}
?>