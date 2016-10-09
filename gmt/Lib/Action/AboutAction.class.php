<?php
class AboutAction extends Action{
   //新手引导
   public function beginnersGuide(){
      
      $this->display();
   } 
   //系统常见问题
   public function commonProblems(){
   
      $this->display();
   }
   //新版本特性介绍
   public function versionIntroduction(){
   
      $this->display();
   }
   //注意事项
   public function mattersNeedingAttention(){
   
      $this->display();
   }

}
?>