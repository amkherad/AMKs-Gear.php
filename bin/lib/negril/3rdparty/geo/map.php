<?php
Mvc::Module('html');
Mvc::Module('util\html\sections');
Mvc::Module('negril\3rdparty\geo\geotypes');

use Negril;

class MapHelperInjection implements IHelper{
	public function GoogleMap($name=null,$value=null,$attrs=null){$h=new GoogleMapBuilder($attrs);return$h;}
}$Html->RegisterHelper(new MapHelperInjection());

abstract class MapBuilder extends GenericElement{
    public function Longitude($value){$this->attrs['lon']=$value;return$this;}
    public function Latitude ($value){$this->attrs['lat']=$value;return$this;}
    public function Lon($value){$this->attrs['lon']=$value;return$this;}
    public function Lat($value){$this->attrs['lat']=$value;return$this;}
    
    public function SetGeoLocation(GeoLocation$value){$this->attrs['lon']=$value->getLongitude();$this->attrs['lat']=$value->getLatitude();return$this;}
    public function SetLonLat($lon,$lat){$this->attrs['lon']=$lon;$this->attrs['lat']=$lat;return$this;}
}

class GoogleMapBuilder extends MapBuilder{
    public function RenderToStream(Stream $s,$context){
        $attrs=$this->GetCustomeAttributes();
        $s->Write("<input type=\"hidden\"$attrs></input>");
    }
}
class OpenStreetMapBuilder extends MapBuilder{
    public function RenderToStream(Stream $s,$context){
        $attrs=$this->GetCustomeAttributes();
        $s->Write("<input type=\"hidden\"$attrs></input>");
    }
}
?>