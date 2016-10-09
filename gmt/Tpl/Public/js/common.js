/*****************
	对话框
*****************/
function DialogBox(width,height,content)
{
	//创建DIV并定位
	this.obj=$(document.createElement("DIV"));
	this.obj.className='dialogBox';
	this.obj.style.display='none';
	this.obj.style.position='absolute';
	this.obj.style.overflow='hidden';
	this.width=width;
	this.obj.style.width=width;
	this.height=height;
	this.obj.style.height=height;

	document.body.appendChild(this.obj);

	this.mask=new Mask();
	this.closed=false;
	this.timerHandle=null;
	this._closeEvent=null;  //关闭时触发的事件

	var self=this;

	//显示
	this.show=function(zIndex)
	{
		self._pos();
		self.obj.style.display='';
		if(zIndex!=null)
		{
			self.mask.show(zIndex);
			self.obj.style.zIndex=zIndex;
		}
		else
		{
			self.mask.show(DialogBox.TopLayer++);
			self.obj.style.zIndex=DialogBox.TopLayer++;
		}
		return self;
	}

	//定位对话框的位置
	this._pos=function()
	{
		var _h=document.body.clientHeight;
		_h=Math.floor((_h-self.height)/2)+parseInt(Browser.ie?document.body.scrollTop:window.pageYOffset)-80;
		if(_h<0) _h=0;
		self.obj.style.top=_h+"px";

		self.obj.style.left="50%";
		self.obj.style.marginLeft='-'+Math.floor(self.width/2)+'px';
	}

	this._hidden=function()
	{
		if(self.timerHandle!=null)
		{
			clearInterval(self.timerHandle);
			self.timerHandle=null;
		}

		self.mask.hidden();
		self.obj.style.display='none';
	}

	this.hidden=function(t)
	{
		if(t!=null && typeof(t)=="number")
		{
			t=parseInt(t);
			if(t>0)
			{
				self.timerHandle=setInterval(self._hidden,t*1000);
				return self;
			}
		}
		else
			self._hidden();
		return self;
	}

	this._close=function()
	{
		if(self.timerHandle!=null)
		{
			clearInterval(self.timerHandle);
			self.timerHandle=null;
		}

		self.mask.close();

		//清除事件和内容
		self.obj.getElements('.hiddenDialog').each(function(i){$(i).removeEvent("click",self._hidden);});
		self.obj.getElements('.closeDialog').each(function(i){$(i).removeEvent("click",self._close);});

		deleteNode(self.obj);

		self.closed=true;

		if(self._closeEvent!=null)
		{
			if(typeof(self._closeEvent)=='string')
				eval(self._closeEvent);
			else
				self._closeEvent();
		}
	}

	this.close=function(t)
	{
		if(t!=null && typeof(t)=="number")
		{
			t=parseInt(t);
			if(t>0)
			{
				self.timerHandle=setInterval(self._close,t*1000);
				return self;
			}
		}
		else
			self._close();
		return self;
	}

	this.closeEvent=function(js)
	{
		self._closeEvent=js;
		return self;
	}

	this.update=function(content)
	{
		self.set(content);
		return self;
	}

	//是否在显示
	this.isShow=function()
	{
		if(self.closed || self.obj.style.display=='none')
			return false;
		else
			return true;
	}

	//是否已经关闭
	this.isClosed=function() { return self.closed; }

	//设置内容
	this.set=function(content)
	{
		if(content!=null)
		{
			//清除原有的内容
			self.obj.getElements('.hiddenDialog').each(function(i){$(i).removeEvent("click",self._hidden);});
			self.obj.getElements('.closeDialog').each(function(i){$(i).removeEvent("click",self._close);});
			this.obj.innerHTML='';

			if(typeof(content)=='object')
				this.obj.appendChild(content)
			else if(typeof(content)=='string')
				this.obj.innerHTML=content;

			self.obj.getElements('.hiddenDialog').each(function(i){$(i).addEvent("click",self._hidden);});
			self.obj.getElements('.closeDialog').each(function(i){$(i).addEvent("click",self._close);});
		}

		return self;
	}

	this.set(content);  //设置内容
}

DialogBox.TopLayer=1999;

function Mask()
{
	this.obj=document.createElement("DIV");
	this.obj.style.display='none';
	this.obj.style.position='absolute';
	this.obj.style.left='0px';
	this.obj.style.top='0px';
//	this.obj.style.backgroundColor='#000';
//	if($.browser.mozilla)
//		this.obj.style.opacity=0.5;
//	else
//		this.obj.style.filter='Alpha(opacity=50)';
	this.obj.style.backgroundImage="url(ed/img/blank.gif)";
	document.body.appendChild(this.obj);

	var self=this;

	this.resize=function()
	{
		self.obj.style.height=(document.body.clientHeight || document.documentElement.offsetHeight)+'px';
		self.obj.style.width=(document.body.clientWidth || document.documentElement.offsetWidth)+'px';
	}

	this.resize();

	//显示
	this.show=function(zIndex)
	{
		if(!self.closed)
		{
			self.obj.style.display='';
			if(zIndex!=null) self.obj.style.zIndex=zIndex;
			else self.obj.style.zIndex=DialogBox.TopLayer++;

			$(document.body).addEvent('resize',self.resize);
		}
		else alert("Dialog is close!");
	}

	//隐藏
	this.hidden=function()
	{
		self.obj.style.display='none';
		$(document.body).removeEvent('resize',self.resize);
	}

	//关闭
	this.close=function()
	{
		$(document.body).removeEvent('resize',self.resize);
		deleteNode(self.obj);
	}
}

Mask.TopLayer=1999;

//删除页面中的一个节点
function deleteNode(node)
{
	if(node!=null)
	{
		if(Browser.ie)
		{
			node.style.display='none';
			var d=document.createElement('div');
            d.appendChild(node);
            d.innerHTML = '';
			d.removeNode();
		}
		else
		{
			node.innerHTML='';
			if(node.parentNode!=null)
			{
				node.parentNode.removeChild(node);
			}
			else
			{
				try
				{
					document.body.removeChild(node);
				}
				catch(e)
				{
					alert(e);
				}
			}
		}
	}
}

function DataObject(data)
{
    if(data) this.data=data;
    else this.data={};
    
    var self=this;
    
    this.getValue=function(value)
    {
        var v;
        var type=typeOf(value);
        switch(type)
        {
        case 'element':
            v=value.value;
            break;
        default:
            v=value;
        }
        
        return v;
    }
    
    this.addValue=function(name, value, defaultValue)
    {
        if(value==null || (value+"").length<1)
        {
            if(defaultValue!=null)
            {
                self.data[name]=defaultValue;
            }
        }
        else self.data[name]=value;
    }    
    
    this.addString=function(name, value, defaultValue)
    {
        value=self.getValue(value)+"";
        self.addValue(name, value, defaultValue);
    }
    
    this.addInt=function(name, value, defaultValue)
    {
        value=parseInt(self.getValue(value));
        self.addValue(name, isNaN(value)?null:value, defaultValue);
    }
    
    this.addFloat=function(name, value, defaultValue)
    {
        value=parseFloat(self.getValue(value));
        self.addValue(name, isNaN(value)?null:value, defaultValue);
    }
    
    this.addBool=function(name, value, defaultValue)
    {
        value=(self.getValue(value)+"").toLowerCase();
        if(value=='true' || parseInt(value)>0) value=true;
        else if(value=='null' || value=='') value=null;
        else value=false;
        self.addValue(name, value, defaultValue);
    }
    
    this.value=function(name, defaultValue)
    {
        var v=self.data[name];
        if((v==null || (v+"").length<1) && defaultValue!=null) return defaultValue;
        return v;
    }
    
    this.getString=function(name, defaultValue)
    {
        var v=self.value(name);
        if(v==null) return "";
        else return v+"";
    }
    
    this.extract=function(nameList)
    {
        if(nameList==null) return this.data;
        else
        {
            var obj={};
            
            for(var i=0; i<nameList.length; i++)
            {
                if(this.data[nameList[i]]!=null)
                {
                    obj[nameList[i]]=this.data[nameList[i]];
                }
            }
            
            return obj;
        }
    }

	this.toSimpleString=function()
	{
		var v="";
		if(self.data)
		{
			for(var key in self.data)
			{
				v+=key+'('+self.getString(key)+') ';
			}
		}
		return v;
	}
}


/*****************
	公用对话框
*****************/

//选择任务对话框
function SelectQuestDialog(callback)
{
	if(SelectQuestDialog.self)
	{
		SelectQuestDialog.self.callback=callback;
		SelectQuestDialog.self.init();
		SelectQuestDialog.self.dialog.show();
		return SelectQuestDialog.self;
	}
	else SelectQuestDialog.self=this;

	this.callback=callback;	//回调方法
	this.dialog=null;		//对话框对象
	this.data=null;			//任务数据的数组
	
	new ProAjax("quest", "questDialog", this._create).send();
}

SelectQuestDialog.self=null;

SelectQuestDialog.prototype.init=function()
{
	//处理每次显示时需要清除的状态
}

SelectQuestDialog.prototype._create=function(data)
{
	if(data)
	{
		SelectQuestDialog.self.dialog=new DialogBox(data.width, data.height, data.html);
		SelectQuestDialog.self.init();
		SelectQuestDialog.self.dialog.show();
		SelectQuestDialog.self.query();
	}
}

SelectQuestDialog.prototype.query=function()
{
	new ProAjax("quest", "questDialogQuery", this._query, $('questDialogQueryForm')).send();
}

SelectQuestDialog.prototype._query=function(data)
{
	if(data)
	{
		SelectQuestDialog.self.data=data;

		var select=$('questDialogQueryList');
		select.options.length=0;
		for(var i=0; i<data.length; i++)
		{
			select.options[select.options.length]=new Option(data[i].questId+" "+data[i].name, data[i]._id);
		}
	}
}

SelectQuestDialog.prototype.selectQuest=function()
{
	var select=$('questDialogQueryList');
	if(select.selectedIndex>-1)
	{
		var quest=SelectQuestDialog.self.data[select.selectedIndex];
		if(quest && SelectQuestDialog.self.callback)
		{
			SelectQuestDialog.self.callback(quest);
			SelectQuestDialog.self.dialog.hidden();
		}
	}
}

//选择任务线对话框
function SelectQuestLineDialog(callback)
{
	if(SelectQuestLineDialog.self)
	{
		SelectQuestLineDialog.self.callback=callback;
		SelectQuestLineDialog.self.init();
		SelectQuestLineDialog.self.dialog.show();
		return SelectQuestLineDialog.self;
	}
	else SelectQuestLineDialog.self=this;

	this.callback=callback;	//回调方法
	this.dialog=null;		//对话框对象
	this.data=null;			//任务数据的数组
	
	new ProAjax("quest", "questLineDialog", this._create).send();
}

SelectQuestLineDialog.self=null;

SelectQuestLineDialog.prototype.init=function()
{
	//处理每次显示时需要清除的状态
}

SelectQuestLineDialog.prototype._create=function(data)
{
	if(data)
	{
		SelectQuestLineDialog.self.dialog=new DialogBox(data.width, data.height, data.html);
		SelectQuestLineDialog.self.init();
		SelectQuestLineDialog.self.dialog.show();
		SelectQuestLineDialog.self.query();
	}
}

SelectQuestLineDialog.prototype.query=function()
{
	new ProAjax("quest", "questLineDialogQuery", this._query, $('questLineDialogQueryForm')).send();
}

SelectQuestLineDialog.prototype._query=function(data)
{
	if(data)
	{
		SelectQuestLineDialog.self.data=data;

		var select=$('questLineDialogQueryList');
		select.options.length=0;
		for(var i=0; i<data.length; i++)
		{
			select.options[select.options.length]=new Option(data[i].questLineId+" "+data[i].name, data[i]._id);
		}
	}
}

SelectQuestLineDialog.prototype.selectQuest=function()
{
	var select=$('questLineDialogQueryList');
	if(select.selectedIndex>-1)
	{
		var quest=SelectQuestLineDialog.self.data[select.selectedIndex];
		if(quest && SelectQuestLineDialog.self.callback)
		{
			SelectQuestLineDialog.self.callback(quest);
			SelectQuestLineDialog.self.dialog.hidden();
		}
	}
}


//NPC对话框

function SelectNPCDialog(callback)
{
	if(SelectNPCDialog.self)
	{
		SelectNPCDialog.self.callback=callback;
		SelectNPCDialog.self.init();
		SelectNPCDialog.self.dialog.show();
		return SelectNPCDialog.self;
	}
	else SelectNPCDialog.self=this;

	this.callback=callback;	//回调方法
	this.dialog=null;		//对话框对象
	this.data=null;			//任务数据的数组
	
	new ProAjax("quest", "npcDialog", this._create).send();
}

SelectNPCDialog.self=null;

SelectNPCDialog.prototype.init=function()
{
	//处理每次显示时需要清除的状态
}

SelectNPCDialog.prototype._create=function(data)
{
	if(data)
	{
		SelectNPCDialog.self.dialog=new DialogBox(data.width, data.height, data.html);
		SelectNPCDialog.self.init();
		SelectNPCDialog.self.dialog.show();
		SelectNPCDialog.self.scene();
	}
}

SelectNPCDialog.prototype.scene=function()
{
	new ProAjax("quest", "npcDialogScene", this._scene).send();
}

SelectNPCDialog.prototype._scene=function(data)
{
	if(data)
	{
		var list=$('npcDialogSceneList');
		for(var i=0; i<data.length; i++)
		{
			list.options.add(new Option(data[i].sceneId+" "+data[i].name, data[i]._id));
		}
	}
}

SelectNPCDialog.prototype.selectScene=function()
{
	var select=$('npcDialogQueryList');
	select.options.length=0;

	var sceneId=$('npcDialogSceneList').value;
	if(sceneId!=null && sceneId.length>0)
	{
		new ProAjax("quest", "npcDialogQuery", this._query, {sceneId:sceneId}).send();
	}
}

SelectNPCDialog.prototype._query=function(data)
{
	if(data)
	{
		SelectNPCDialog.self.data=data;

		var select=$('npcDialogQueryList');
		for(var i=0; i<data.length; i++)
		{
			select.options.add(new Option(data[i].gId+" "+data[i].name, data[i]._id));
		}
	}
}

SelectNPCDialog.prototype.selectNPC=function()
{
	var select=$('npcDialogQueryList');
	if(select.selectedIndex>-1)
	{
		var quest=SelectNPCDialog.self.data[select.selectedIndex];
		if(quest && SelectNPCDialog.self.callback)
		{
			SelectNPCDialog.self.callback(quest);
			SelectNPCDialog.self.dialog.hidden();
		}
	}
}