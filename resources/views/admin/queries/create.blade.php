<x-app-layout>
    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    <h2>Assign Query</h2>

                    <form action="{{ route('admin.queries.store') }}" method="POST">
                        @csrf
                        <div class="mb-4">
                            <label for="project_id" class="block text-gray-700 text-sm font-bold mb-2">Project</label>
                            <select name="project_id" id="project_id" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline">
                                @foreach ($projects as $project)
                                    <option value="{{ $project->id }}">{{ $project->name }}</option>
                                @endforeach
                            </select>
                        </div>

                        <div class="mb-4">
                            <label for="user_id" class="block text-gray-700 text-sm font-bold mb-2">Employee</label>
                            <select name="user_id" id="user_id" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline">
                                @foreach ($employees as $employee)
                                    <option value="{{ $employee->id }}">{{ $employee->username }}</option>
                                @endforeach
                            </select>
                        </div>

                        <div class="mb-4">
                            <label for="title" class="block text-gray-700 text-sm font-bold mb-2">Title</label>
                            <input type="text" name="title" id="title" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline">
                        </div>

                        <div class="mb-4">
                            <label for="query_details" class="block text-gray-700 text-sm font-bold mb-2">Query Details</label>
                            <textarea name="query_details" id="query_details" rows="4" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline"></textarea>
                        </div>

                        <div class="mb-4">
                            <label for="assigned_date" class="block text-gray-700 text-sm font-bold mb-2">Assigned Date</label>
                            <input type="date" name="assigned_date" id="assigned_date" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline">
                        </div>

                        <div class="mb-4">
                            <label for="start_date" class="block text-gray-700 text-sm font-bold mb-2">Start Date</label>
                            <input type="date" name="start_date" id="start_date" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline">
                        </div>

                        <div class="mb-4">
                            <label for="end_date" class="block text-gray-700 text-sm font-bold mb-2">End Date</label>
                            <input type="date" name="end_date" id="end_date" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline">
                        </div>

                        <button type="submit" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded focus:outline-none focus:shadow-outline">Assign Query</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>