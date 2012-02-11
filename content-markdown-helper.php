<section id="markdown-helper">
  <h1><?php _ex( 'Markdown Cheatsheet', 'markdown', 'v2press' ); ?></h1>
  <div class="markdown-helper-content">   
    <div class="facebox-col">
        
      <h2><?php _ex( 'Paragraph', 'markdown', 'v2press' ); ?></h2>
      <pre class="facebox-pre">The first paragraph and....
            
a empty line between the second one.</pre>
          
      <h2><?php _ex( 'Headers', 'markdown', 'v2press' ); ?></h2>
      <pre class="facebox-pre"># Header1
## Header2
###### Header6</pre>

      <h2><?php _ex( 'Text styles', 'markdown', 'v2press' ); ?></h2>
      <pre class="facebox-pre">*This text will be italic*
_This will also be italic_
**This text will be bold**
__This will also be bold__

*You **can** combine them*</pre>
    </div>
        
    <div class="facebox-col">
        
      <h2><?php _ex( 'Unordered List', 'markdown', 'v2press' ); ?></h2>
      <pre class="facebox-pre">* Item 1
* Item 2
  * Item 2a
  * Item 2b</pre>
  
      <h2><?php _ex( 'Ordered List', 'markdown', 'v2press' ); ?></h2>
      <pre class="facebox-pre">1. Item 1
2. Item 2
3. Item 3
  * Item 3a
  * Item 3b</pre>
    
      <h2><?php _ex( 'Images', 'markdown', 'v2press' ); ?></h2>  
      <pre class="facebox-pre">![Alt Text](url)</pre>
        
      <h2><?php _ex( 'Links', 'markdown', 'v2press' ); ?></h2>
      <pre class="facebox-pre">http://google.com - automatic!
[Google](http://google.com)</pre>
    </div>
        
    <div class="facebox-col last"> 
      <h2><?php _ex( 'Blockquotes', 'markdown', 'v2press' ); ?></h2>
      <pre class="facebox-pre">As Kanye West said:

> We're living the future so
> the present is our past.</pre>

      <h2><?php _ex( 'Code Block', 'markdown', 'v2press' ); ?></h2>
      <pre class="facebox-pre">```
function fancyAlert(arg) {
  if(arg) {
    $.facebox({div:'#foo'})
  }
}
```</pre>

      <h2><?php _ex( 'Inline code', 'markdown', 'v2press' ); ?></h2>
      <pre class="facebox-pre">I think you should use an
`&lt;addr&gt;` element here instead.</pre>
    </div>
  </div>
  <div class="more">
    <?php printf( __( 'For more syntax references, please visit %1$sMarkdown Syntax%2$s', 'v2press' ), '<a href="http://daringfireball.net/projects/markdown/syntax" title="Markdown Syntax">', '</a>' ); ?>
  </div>
</section>