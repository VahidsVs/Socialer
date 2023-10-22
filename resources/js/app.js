import './bootstrap';
import Search from './live-search';
import Chat from './chat';
import SPA from './spa-profile';

if(document.querySelector('.header-search-icon')) // only if there is search icon and user is logined instanciate Search
{
    new Search();
}
if(document.querySelector('.header-chat-icon')) // only if there is chat icon and user is logined instanciate Chat
{
    new Chat();
}
if(document.querySelector('.profile-nav')) // only if there is chat icon and user is logined instanciate Chat
{
   new SPA();
}