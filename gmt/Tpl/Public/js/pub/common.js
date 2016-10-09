function ProAjax(action, method, callback, data, temp)
{
    this.action=this._checkName(action);
    this.method=this._checkName(method);
    this.callback=callback;
    this.temp=temp;

    this.data="";
    this.form=null;
    this.json=null;

    if(data!=null)
    {
        if(typeof(data)=='object')
        {
            if(data.nodeName=='FORM') this.form=data;
            else this.json=data;
        }
        else this.json={'v':data};
    }

    var self=this;
    this.callbackWrapper=function(data)
    {
        if(self.callback)
        {
            try
            {
                eval("data="+data);
            }
            catch(e)
            {
                alert("Ajax Error: "+e.message+"\n"+data);
                return;
            }

            if(self.temp) self.callback(data, temp);
            else self.callback(data);
        }
    }
}

ProAjax.prototype.addData=function(data)
{
    if(data)
    {
        if(this.data.length>0) this.data+="&"+data;
        else this.data=data;
    }

    return this;
}

ProAjax.prototype.addValue=function(name, value)
{
    if(typeof(value)=='object') value=JSON.encode(value);
    value=encodeURIComponent(value+"");
    this.addData(name+"="+value);

    return this;
}

ProAjax.prototype.send=function()
{
    if(this.form) this.addData($(this.form).toQueryString());
    if(this.json)
    {
        for(var key in this.json)
        {
            this.addValue(key, this.json[key]);
        }
    }

    this.addValue('_ajax', '');

    var myRequest = new Request({method:'post', url:this.action+'.'+this.method+'.htm', onSuccess:this.callbackWrapper});
    myRequest.send(this.data);

    return this;
}

ProAjax.prototype._checkName=function(str)
{
    var temp="";

    if(str)
    {
        for(var i=0; i<str.length; i++)
        {
            var c=str.charAt(i);
            if(c>='A' && c<='Z')
                temp+="_"+c.toLowerCase();
            else temp+=c;
        }
    }

    return temp;
}
