
<main class="wrap">
    
  <section class="container">
    <div class="container__heading">
        
        <div class="text-center">
        <div class="flex justify-center">
            <a href="{{ route('home') }}" class="inline-block">
                <x-logo/>
            </a>
        </div>

        <h2>Terms & Conditions</h2>

       
    </div>
    
      
    </div>
    <div class="container__content">
      <p>Lorem ipsum dolor sit amet, consectetur adipiscing elit. Integer nec odio. Praesent libero. Sed cursus ante dapibus diam. Sed nisi. Nulla quis sem at nibh elementum imperdiet. Duis sagittis ipsum. Praesent mauris. Fusce nec tellus sed augue semper porta. Mauris massa. Vestibulum lacinia arcu eget nulla. Class aptent taciti sociosqu ad litora torquent per conubia nostra, per inceptos himenaeos. Curabitur sodales ligula in libero. Sed dignissim lacinia nunc. </p>
      <p>Curabitur tortor. Pellentesque nibh. Aenean quam. In scelerisque sem at dolor. Maecenas mattis. Sed convallis tristique sem. Proin ut ligula vel nunc egestas porttitor. Morbi lectus risus, iaculis vel, suscipit quis, luctus non, massa. Fusce ac turpis quis ligula lacinia aliquet. Mauris ipsum. Nulla metus metus, ullamcorper vel, tincidunt sed, euismod in, nibh. Quisque volutpat condimentum velit. </p>
      <p>Class aptent taciti sociosqu ad litora torquent per conubia nostra, per inceptos himenaeos. Nam nec ante. Sed lacinia, urna non tincidunt mattis, tortor neque adipiscing diam, a cursus ipsum ante quis turpis. Nulla facilisi. Ut fringilla. Suspendisse potenti. Nunc feugiat mi a tellus consequat imperdiet. Vestibulum sapien. Proin quam. Etiam ultrices. Suspendisse in justo eu magna luctus suscipit. </p>
      <p>Sed lectus. Integer euismod lacus luctus magna. Quisque cursus, metus vitae pharetra auctor, sem massa mattis sem, at interdum magna augue eget diam. Vestibulum ante ipsum primis in faucibus orci luctus et ultrices posuere cubilia Curae; Morbi lacinia molestie dui. Praesent blandit dolor. Sed non quam. In vel mi sit amet augue congue elementum. Morbi in ipsum sit amet pede facilisis laoreet. Donec lacus nunc, viverra nec, blandit vel, egestas et, augue. Vestibulum tincidunt malesuada tellus. Ut ultrices ultrices enim. Curabitur sit amet mauris. </p>
      <p>Morbi in dui quis est pulvinar ullamcorper. Nulla facilisi. Integer lacinia sollicitudin massa. Cras metus. Sed aliquet risus a tortor. Integer id quam. Morbi mi. Quisque nisl felis, venenatis tristique, dignissim in, ultrices sit amet, augue. Proin sodales libero eget ante. Nulla quam. Aenean laoreet. </p>
    </div>
    <div class="container__nav">
      <small>Thank you</small>
      
    </div>
  </section>
</main>
<style>
    .text-center{
        text-align: center;
    }
    .wrap {
  display: flex;
  justify-content: space-around;
  align-items: center;
  box-sizing: border-box;
  height: 100vh;
  padding: 2rem;
  background-color: #fafafa;
}

.container {
  display: flex;
  flex-direction: column;
  box-sizing: border-box;
  padding: 1rem;
  background-color: #fff;
  width: 768px;
  height: 100%;
  border-radius: 0.25rem;
  box-shadow: 0rem 1rem 2rem -0.25rem rgba(0, 0, 0, 0.25);
  
  &__heading {
    padding: 1rem 0;
    border-bottom: 1px solid #ccc;
    text-align: center;
    
    & > h2 {
      font-size: 1.75rem;
      line-height: 1.75rem;
      margin: 0;
    }
  }
  
  &__content {
    flex-grow: 1;
    overflow-y: scroll;
  }
  
  &__nav {
    border-top: 1px solid #ccc;
    text-align: right;
    padding: 2rem 0 1rem;
    
    & > .button {
      background-color: #444499;
      box-shadow: 0rem 0.5rem 1rem -0.125rem rgba(0, 0, 0, 0.25);
      padding: 0.8rem 2rem;
      border-radius: 0.5rem;
      color: #fff;
      text-decoration: none;
      font-size: 0.9rem;
      transition: transform 0.25s, box-shadow 0.25s;
      
      &:hover {
        box-shadow: 0rem 0rem 1rem -0.125rem rgba(0, 0, 0, 0.25);
        transform: translateY(-0.5rem);
      }
    }
    
    & > small {
      color: #777;
      margin-right: 1rem;
    }
  }
}
</style>