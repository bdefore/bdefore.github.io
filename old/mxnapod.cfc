<cfsetting enablecfoutputonly="true">
<!---
    Name : mxna.cfm
    Author : Ryan Guill
    Created : April 8, 2005
    Last Updated : 
    History : 
    Purpose       : To show the most recent posts on weblogs.macromedia.com/mxna/ using the webservice.
 --->
    <cfsilent>
       <cfinvoke 
        webservice="http://weblogs.macromedia.com/mxna/webservices/mxna2.cfc?wsdl"
        method="getMostRecentPosts"
        returnvariable="qGetMostRecentPosts">
          <cfinvokeargument name="limit" value="15"/>
          <cfinvokeargument name="offset" value="0"/>
          <cfinvokeargument name="languageIds" value="1"/>
       </cfinvoke>
    </cfsilent>
    <cfoutput>
    <p>
    <div class="rightMenu">
    <div class="menuTitle">MXNA Recent Posts</div>
    <div class="menuBody">
       <p>Recent posts on <a href="http://weblogs.macromedia.com/mxna/">mxna</a></p>
       <ol>
          <cfloop query="qGetMostRecentPosts">
             <li><a href="#qGetMostRecentPosts.postLink#" title="#qGetMostRecentPosts.postTitle#- clicks:#qGetMostRecentPosts.clicks#">#qGetMostRecentPosts.postTitle#</a></li>
          </cfloop>
       </ol>
       <p>Built with the <a href="http://weblogs.macromedia.com/mxna/Developers.cfm" target="_blank">mxna webservice</a></p>
    </div>
    </div>
    </p>
    </cfoutput>
<cfsetting enablecfoutputonly="false">