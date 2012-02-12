<section id="markdown-helper">
  <h1><?php _ex( 'Markdown Cheatsheet', 'helper', 'v2press' ); ?></h1>
  <div class="helper-content">   
    <div class="facebox-col">
        
      <h2><?php _ex( 'Paragraph', 'helper', 'v2press' ); ?></h2>
      <pre class="facebox-pre">The first paragraph and....
            
a empty line between the second one.</pre>
          
      <h2><?php _ex( 'Headers', 'helper', 'v2press' ); ?></h2>
      <pre class="facebox-pre"># Header1
## Header2
###### Header6</pre>

      <h2><?php _ex( 'Text styles', 'helper', 'v2press' ); ?></h2>
      <pre class="facebox-pre">*This text will be italic*
_This will also be italic_
**This text will be bold**
__This will also be bold__

*You **can** combine them*</pre>
    </div>
        
    <div class="facebox-col">
        
      <h2><?php _ex( 'Unordered List', 'helper', 'v2press' ); ?></h2>
      <pre class="facebox-pre">* Item 1
* Item 2
  * Item 2a
  * Item 2b</pre>
  
      <h2><?php _ex( 'Ordered List', 'helper', 'v2press' ); ?></h2>
      <pre class="facebox-pre">1. Item 1
2. Item 2
3. Item 3
  * Item 3a
  * Item 3b</pre>
    
      <h2><?php _ex( 'Images', 'helper', 'v2press' ); ?></h2>  
      <pre class="facebox-pre">![Alt Text](url)</pre>
        
      <h2><?php _ex( 'Links', 'helper', 'v2press' ); ?></h2>
      <pre class="facebox-pre">http://google.com - automatic!
[Google](http://google.com)</pre>
    </div>
        
    <div class="facebox-col last"> 
      <h2><?php _ex( 'Blockquotes', 'helper', 'v2press' ); ?></h2>
      <pre class="facebox-pre">As Kanye West said:

> We're living the future so
> the present is our past.</pre>

      <h2><?php _ex( 'Code Block', 'helper', 'v2press' ); ?></h2>
      <pre class="facebox-pre">```
function fancyAlert(arg) {
  if(arg) {
    $.facebox({div:'#foo'})
  }
}
```</pre>

      <h2><?php _ex( 'Inline code', 'helper', 'v2press' ); ?></h2>
      <pre class="facebox-pre">I think you should use an
`&lt;addr&gt;` element here instead.</pre>
    </div>
  </div>
  <div class="more">
    <?php printf( _x( 'For more syntax references, please visit %1$sMarkdown Syntax%2$s', 'helper', 'v2press' ), '<a href="http://daringfireball.net/projects/markdown/syntax" title="Markdown Syntax">', '</a>' ); ?>
  </div>
</section>

<section id="hotkeys-helper">
  <h1><?php _ex( 'Keyboard Shortcuts', 'helper', 'v2press' ); ?></h1>
  <div class="helper-content">
    <div class="facebox-col">
      <h2><?php _ex( 'Global', 'helper', 'v2press' ); ?></h2>
      <p><kbd>m</kbd> <?php _ex( 'Show Markdown Cheatsheet', 'helper', 'v2press' ); ?></p>
      <p><kbd>n</kbd> <?php _ex( 'Create new topic', 'helper', 'v2press' ); ?></p>
      <p><kbd>h</kbd> <?php _ex( 'Show this helper dialog', 'helper', 'v2press' ); ?></p>
    </div>
    
    <div class="facebox-col last">
      <h2><?php _ex( 'Topic Page', 'helper', 'v2press' ); ?></h2>
      <p><kbd>b</kbd> <?php _ex( 'Bookmark this topic', 'helper', 'v2press' ); ?></p>
      <p><kbd>B</kbd> <?php _ex( 'Unbookmark this topic', 'helper', 'v2press' ); ?></p>
      
      <h2><?php _ex( 'Member Page', 'helper', 'v2press' ); ?></h2>
      <p><kbd>f</kbd> <?php _ex( 'Follow this member', 'helper', 'v2press' ); ?></p>
      <p><kbd>F</kbd> <?php _ex( 'Unfollow this member', 'helper', 'v2press' ); ?></p>
    </div>
  </div>
</section>