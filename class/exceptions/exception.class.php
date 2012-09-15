<?php
	
	namespace Exceptions;

	abstract class CoreException extends \Exception
	{

	}

	class KeyError extends CoreException
	{

	}

	class PageNotFound extends CoreException
	{

	}

	class NotAuthorized extends CoreException
	{

	}
	
	class PageError extends CoreException
	{

	}

	class RunTimeError extends CoreException
	{

	}