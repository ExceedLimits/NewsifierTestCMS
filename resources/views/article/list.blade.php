<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            Articles
        </h2>
    </x-slot>

    <x-slot name="action">
        <a type="button" href="{{ route('articles.create') }}"
          class="px-6 py-3 text-blue-100 no-underline bg-blue-500 rounded hover:bg-blue-600 hover:no-underline hover:text-blue-200">Add
          article</a>
    </x-slot>
  <div class="block max-w-7xl mx-auto px-4 pt-8 pb-2.5 bg-white rounded-lg border border-gray-200 shadow-md hover:bg-gray-100 dark:bg-gray-800 dark:border-gray-700 dark:hover:bg-gray-700 ">      
      <div class="row">
              <div class="overflow-x-auto relative">
                  <table class="w-full text-sm text-left text-gray-500 dark:text-gray-400"
                      id="laravel_unique_slug_table">
                      <thead class="text-xs text-gray-700 uppercase bg-gray-50 dark:bg-gray-700 dark:text-gray-400">
                          <tr>
                              <th class="py-3 px-0.5 text-center bg-blue-200">ID</th>
                              <th class="py-3 px-3 w-3/4">Title</th>                           
                              <th class="py-3 px-3" colspan="2">Actions</th>
                          </tr>
                      </thead>
                      <tbody>
                          @foreach ($articles as $article)
                              <tr class="bg-white border-b dark:bg-gray-800 dark:border-gray-700">
                                <th class="py-4 px-0.5 text-center font-medium text-gray-900 whitespace-nowrap dark:text-white bg-blue-200">
                                    {{ $article->id }}
                                </th>
                                <td class="py-4 px-3 w-3/4"><a href="{{route('show',$article)}}">{{ $article->title }}</a></td>
                                <td class="py-4 px-3">
                                    <a class="text-yellow-500 font-bold" href="{{route('articles.edit',$article->id)}}" >Edit</a>                                   
                                </td>
                                <td class="py-4 px-3">
                                    <form action="{{ route('articles.destroy', $article->id)}}" method="post">
                                      @csrf
                                      @method('DELETE')
                                      <button class="text-red-500 font-bold" onclick="return confirm('Are you sure?')" type="submit">Delete</button>
                                    </form>
                                </td>
                                <!--td class="py-4 px-6">
                                    <a class="text-red-500 font-bold" href="{{route('articles.destroy',$article)}}" >Delete</a>                                    
                                </td--> 
                              </tr>
                          @endforeach
                      </tbody>
                  </table>
                  {!! $articles->links() !!}
              </div>
      </div>
  </div>
</x-app-layout>
