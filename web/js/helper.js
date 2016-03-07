/**
 * Created by slashman on 10.03.15.
 */

String.prototype.trunc = String.prototype.trunc ||
function(n){
    return this.length>n ? this.substr(0,n-1)+'&hellip;' : this;
};